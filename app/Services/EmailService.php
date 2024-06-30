<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;


class EmailService
{
    protected $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->configure();
    }

    protected function configure()
    {
        $this->mailer->isSMTP();
        $this->mailer->Host = env('MAIL_HOST'); 
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = env('MAIL_USERNAME'); 
        $this->mailer->Password = env('MAIL_PASSWORD');
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->Port = 587;
        $this->mailer->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    }

    public function sendEmail($to, $subject, $body,$isHtml=true)
    {
        try {
            $this->mailer->addAddress($to);
            $this->mailer->isHTML($isHtml);
            $this->mailer->Subject = $subject;
            $this->mailer->Body  = $body;
            $this->mailer->send();
        } catch (Exception $e) {
            Log::error('Failed to send email', ['to' => $to, 'error' => $e->getMessage()]);
            throw new \Exception('Failed to send email: ' . $e->getMessage());
        }
    }
}
