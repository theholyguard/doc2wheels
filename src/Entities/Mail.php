<?php

namespace App\Entities;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['SMTP_HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $_ENV['SMTP_USERNAME'];
        $this->mail->Password = $_ENV['SMTP_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = $_ENV['SMTP_PORT'];
        $this->mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
    }

    public function send($to, $subject, $message) {
        try {
            $this->mail->addAddress($to);
            $this->mail->isHTML(false);
            $this->mail->Subject = $subject;
            $this->mail->Body = $message;
            $this->mail->send();
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}");
        }
    }
}
?>