<?php 

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\CampaignRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use App\Jobs\ProcessCampaignEmail;
use App\Services\EmailService;
use Illuminate\Validation\Rule;


class CampaignRepository implements CampaignRepositoryInterface
{
    /**
     * Get all campaigns with records
     * @param int $userId
     * @param int $perPage
     * @param string|null $search
     * @author Ashish Kumar
     */
    public function getAllCampaignsWithRecords($userId, $perPage, $search = null){
        $query = Campaign::where('user_id', $userId)
            ->with(['records' => function($query) {
                $query->select('id', 'campaign_id'); 
            }])
            ->withCount([
                'records',
                'records as pending_records_count' => function ($query) {
                    $query->where('status', 'pending');
                },
                'records as processed_records_count' => function ($query) {
                    $query->where('status', 'sent');
                },
                'records as failed_records_count' => function ($query) {
                    $query->where('status', 'failed');
                },
            ])
            ->orderBy('created_at', 'desc');
    
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
    
        return $query->paginate($perPage);
    }
    
    /**
     * Create a new campaign
     * @param array $data
     * @param string $filePath
     * @author Ashish Kumar
     */
    public function createCampaign(array $data, $filePath){
        $validationResult = $this->validateCSV($filePath);
        if ($validationResult['hasErrors']) {
            return [
                'hasErrors' => true,
                'errorFileName' => $validationResult['errorFileName'],
            ];
        }

        $campaign = Campaign::create([
            'user_id' => Auth::id(),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $this->processCSV($filePath, $campaign->id);
        $this->queueEmails($campaign);

        return [
            'hasErrors' => false,
            'campaign' => $campaign,
        ];
    }


    /**
     * Get campaign by id
     * @param int $id
     * @author Ashish Kumar
     */
    public function getCampaignById($id){
        return Campaign::with('records')->withCount('records')->findOrFail($id);
    }


    /**
     * Update a campaign
     * @param int $id
     * @param array $data
     * @param string|null $filePath
     * @author Ashish Kumar
     */
    public function updateCampaign($id, array $data, $filePath = null){
        $campaign = Campaign::findOrFail($id);
        $campaign->update($data);
        if ($filePath) {
            $validationResult = $this->validateCSV($filePath, true, $id);
            if ($validationResult['hasErrors']) {
                return [
                    'hasErrors' => true,
                    'errorFileName' => $validationResult['errorFileName'],
                ];
            }
            CampaignRecord::where('campaign_id', $id)->delete();
            $this->processCSV($filePath, $id);
            $this->queueEmails($campaign);
        }

        return [
            'hasErrors' => false,
            'campaign' => $campaign,
        ];
    }

    /**
     * Delete a campaign
     * @param int $id
     * @author Ashish Kumar
     */
    public function deleteCampaign($id){
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();
    }

    /**
     * Get all records of a campaign
     * @param int $campaignId
     * @param int $perPage
     * @param string|null $search
     * @param string|null $status
     * @author Ashish Kumar
     */
    private function validateCSV($filePath, $isUpdate = false, $campaignId = null){
        $file = fopen(Storage::path($filePath), 'r');
        $errors = [];
        $rowNumber = 1;
        $headers = fgetcsv($file);
        $headers = array_map(function($header) {
            return strtolower(str_replace(' ', '_', $header));
        }, $headers);
    
        $existingEmails = CampaignRecord::where('campaign_id', $campaignId)->pluck('email')->toArray();
        $fileEmails = [];
        $errorInCSV = false;
        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            $rowErrors = [];
            $recordData = array_combine($headers, $data);
    
            // Check for duplicate emails in the CSV file
            if (in_array($recordData['email'], $fileEmails)) {
                $errorInCSV = true;
                $rowErrors[] = 'Email is duplicated within the file.';
            } else {
                $fileEmails[] = $recordData['email'];
            }
    
            if($isUpdate && in_array($recordData['email'], $existingEmails)){
                $errorInCSV = true;
                $rowErrors[] = 'Email already exists in the records.';
            }

            $validator = Validator::make($recordData, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'content' => 'required|string',
                'campaign_date' => 'required|date',
            ]);
    
            if ($validator->fails()) {
                $errorInCSV = true;
                $rowErrors = array_merge($rowErrors, $validator->errors()->all());
            }
    
            if (!empty($errorInCSV)) {
                $errors[$rowNumber] = [
                    'data' => $recordData,
                    'errors' => $rowErrors
                ];
            }
    
            $rowNumber++;
        }
    
        fclose($file);
    
        if (!empty($errors)) {
            $errorFileName = $this->generateErrorFile($headers, $errors);
            return ['hasErrors' => true, 'errorFileName' => $errorFileName];
        }
    
        return ['hasErrors' => false];
    }
    

    /**
     * Generate error file
     * @param array $header
     * @param array $errors
     * @author Ashish Kumar
     */
    private function generateErrorFile($header, $errors){
        $errorFileName = 'errors_' . md5(Auth::id() . time()) . '.csv';
        $errorFilePath = 'uploads/error_files/' . $errorFileName;
        if (!file_exists(Storage::path('uploads/error_files'))) {
            mkdir(Storage::path('uploads/error_files'), 0777, true);
        }
        $file = fopen(Storage::path($errorFilePath), 'w');
        $header[] = 'Errors';
        fputcsv($file, $header);
        foreach ($errors as $rowNumber => $row) {
            $data = $row['data'];
            $rowErrors = implode('; ', $row['errors']);
            fputcsv($file, array_merge($data, [$rowErrors]));
        }
        fclose($file);
        return $errorFileName;
    }

    /**
     * Process CSV file
     * @param string $filePath
     * @param int $campaignId
     * @author Ashish Kumar
     */
    private function processCSV($filePath, $campaignId){
        $file = fopen(Storage::path($filePath), 'r');
        $headers = fgetcsv($file);
        $headers = array_map(function($header) {
            return strtolower(str_replace(' ', '_', $header));
        }, $headers);
        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            $recordData = array_combine($headers, $data);   
            CampaignRecord::create([
                'campaign_id' => $campaignId,
                'name' => $recordData['name'] ?? null,
                'email' => $recordData['email'] ?? null,
                'content' => $recordData['content'] ?? null,
                'campaign_date' => $recordData['campaign_date'] ?? null,
            ]);
        }
        fclose($file);
    }
    


    /**
     * Queue emails
     * @param Campaign $campaign
     * @author Ashish Kumar
     */
    private function queueEmails(Campaign $campaign) {
       if($campaign->records->isEmpty()){
           return;
        }
        $emailService = new EmailService();
        foreach ($campaign->records as $record) {
            if ($record->status == 'pending') {
                ProcessCampaignEmail::dispatch($campaign->name, $record, $emailService)->onQueue('emails');
            }
        }
    }
}
