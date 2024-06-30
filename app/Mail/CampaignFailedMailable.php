<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class CampaignFailedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $name;
    public $content;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $name
     * @param string $content
     */
    public function __construct($subject, $record_email, $content)
    {
        $this->subject = $subject;
        $this->record_email = $record_email;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.campaign-failed')
                    ->with([
                        'subject' => $this->subject,
                        'content' => $this->content,
                    ]);
    }
}
