<?php 

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\CampaignRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CampaignRepository implements CampaignRepositoryInterface
{
    public function getAllCampaignsWithRecords($userId, $perPage){
        return Campaign::where('user_id', $userId)
            ->with('records')
            ->withCount('records')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getAllCampaigns($userId, $perPage){
        return Campaign::where('user_id', $userId)->paginate($perPage);
    }

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

        return [
            'hasErrors' => false,
            'campaign' => $campaign,
        ];
    }

    public function getCampaignById($id){
        return Campaign::with('records')->withCount('records')->findOrFail($id);
    }

    public function updateCampaign($id, array $data){
        $campaign = Campaign::findOrFail($id);
        $campaign->update($data);
        return $campaign;
    }

    public function deleteCampaign($id){
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();
    }

    private function validateCSV($filePath){
        $file = fopen(Storage::path($filePath), 'r');
        $errors = [];
        $rowNumber = 1;
        $header = fgetcsv($file);

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            $rowErrors = [];

            $validator = Validator::make([
                'name' => $data[0],
                'email' => $data[1],
                'content' => $data[2],
                'campaign_date' => $data[3],
            ], [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'content' => 'required|string',
                'campaign_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $rowErrors = $validator->errors()->all();
            }

            if (!empty($rowErrors)) {
                $errors[$rowNumber] = [
                    'data' => $data,
                    'errors' => $rowErrors
                ];
            }

            $rowNumber++;
        }

        fclose($file);

        if (!empty($errors)) {
            $errorFileName = $this->generateErrorFile($header, $errors);
            return ['hasErrors' => true, 'errorFileName' => $errorFileName];
        }

        return ['hasErrors' => false];
    }

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

    private function processCSV($filePath, $campaignId){
        $file = fopen(Storage::path($filePath), 'r');
        fgetcsv($file);

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            CampaignRecord::create([
                'campaign_id' => $campaignId,
                'name' => $data[0],
                'email' => $data[1],
                'content' => $data[2],
                'campaign_date' => $data[3],
            ]);
        }

        fclose($file);
    }
}
