<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\CampaignRecord;
use Illuminate\Support\Facades\View;
use Exception;
use App\Mail\CampaignEmailMailable;
use App\Models\Campaign;
use Illuminate\Support\Facades\Mail;
use App\Mail\CampaignCompletedMailable;
use App\Mail\CampaignFailedMailable;


class ProcessCampaignEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries;
    public $timeout;

    protected $record;
    protected $campaignName;
    protected $campaignId;
    protected $emailService;

    public function __construct($campaignName, CampaignRecord $record, $emailService)
    {
        $this->record = $record;
        $this->campaignName = $campaignName;
        $this->campaignId = $record->campaign_id;
        $this->emailService = $emailService;
        $this->tries = config('jobs.campaign_email.tries');
        $this->timeout = config('jobs.campaign_email.timeout');
    }
     
    /**
     * Execute the job.
     * @author Ashish Kumar
     */
    public function handle(){
        try {
            Mail::to($this->record->email)->send(new CampaignEmailMailable($this->campaignName, $this->record->name, $this->record->content));
            $this->record->update(['status' => 'sent']);
            $this->checkAndNotifyCampaignOwner();
        } catch (Exception $e) {
            $this->record->update(['status' => 'failed']);
            Log::error('Failed to send campaign email', [
                'record_id' => $this->record->id,
                'error' => $e->getMessage()
            ]);
            $this->notifyFailure($e->getMessage());
            $this->release(60); // Retry after 60 seconds
        }
    }


    /**
     * Handle a job failure.
     * @param  \Exception  $exception
     * @author Ashish Kumar
     */
    public function failed(Exception $exception){
        $this->record->update(['status' => 'failed']);
        Log::error('Permanently failed to send campaign email', [
            'record_id' => $this->record->id,
            'error' => $exception->getMessage()
        ]);
    }

    /**
     * Check and notify campaign owner if all emails have been sent
     * @author Ashish Kumar
     */
    protected function checkAndNotifyCampaignOwner(){
        $campaign = Campaign::with('user')->find($this->campaignId);
        if ($campaign->records()->where('status', 'pending')->doesntExist()) {
           Mail::to($campaign->user->email)->send(new CampaignCompletedMailable('Campaign Completed', 'Hi, All emails have been sent for your campaign ' . $this->campaignName));
        }
    }

    /**
     * Notify campaign owner if email failed to send
     * @param string $errorMessage
     * @author Ashish Kumar
     */
    protected function notifyFailure($errorMessage){
        $campaign = Campaign::with('user')->find($this->campaignId);
        Mail::to($campaign->user->email)->send(new CampaignFailedMailable('Campaign Email Failed','Failed to send email to ' . $this->record->email . ' for campaign ' . $this->campaignName . '. Error: ' . $errorMessage));
    }
}
