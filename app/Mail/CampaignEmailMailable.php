<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class CampaignEmailMailable extends Mailable
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
    public function __construct($subject, $name, $content)
    {
        $this->subject = $subject;
        $this->name = $name;
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
                    ->view('emails.campaign')
                    ->with([
                        'subject' => $this->subject,
                        'username' => $this->name,
                        'content' => $this->content,
                    ]);
    }
}
