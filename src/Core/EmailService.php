<?php

namespace LeanPHP\Core;
use Exception;

class EmailService
{
    private $sender;
    private $replyTo;
    private $returnPath;
    private $appName;

    public function __construct()
    {
        $this->appName = getenv('APP_NAME') ?: 'LeanPHP';
        $this->sender = getenv('EMAIL_SENDER') ?: 'LeanPHP <info@leanphp.io>';
        $this->replyTo = getenv('EMAIL_REPLY_TO') ?: 'LeanPHP Support <support@leanphp.io>';
        $this->returnPath = getenv('EMAIL_RETURN_PATH') ?: 'info@leanphp.io';
    }

    public function sendEmail($email, $subject, $bodyContent)
    {
        $subject = $this->appName . ' - ' . $subject;
        $body = $this->createEmailBody($bodyContent);
        $headers = $this->createEmailHeaders();

        if (mail($email, $subject, $body, $headers)) {
            return 'Mail sent successfully';
        } else {
            throw new Exception('Failed to send email');
        }
    }

    private function createEmailBody($bodyContent)
    {
        $message = '<html><body>';
        $message .= $bodyContent;
        $message .= '</body></html>';
        return $message;
    }

    private function createEmailHeaders()
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: {$this->sender}" . "\r\n";
        $headers .= "Reply-To: {$this->replyTo}" . "\r\n";
        $headers .= "Return-Path: {$this->returnPath}" . "\r\n";
        return $headers;
    }
}
