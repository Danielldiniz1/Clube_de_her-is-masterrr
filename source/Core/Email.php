<?php

namespace Source\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = CONF_MAIL_HOST;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = CONF_MAIL_USER;
        $this->mail->Password = CONF_MAIL_PASS;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = CONF_MAIL_PORT;
        $this->mail->CharSet = 'UTF-8';
        $this->mail->setFrom(CONF_MAIL_SENDER_EMAIL, CONF_MAIL_SENDER_NAME);
    }

    public function sendEmail(string $to, string $subject, string $body): bool
    {
        try {
            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;

            return $this->mail->send();
        } catch (Exception $e) {
            // Você pode logar o erro para depuração
            error_log("Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}");
            return false;
        }
    }
}