<?php

require_once __DIR__ .'/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true); // Passing `true` enables exceptions

    try {
        // Server settings
        $mail->SMTPDebug = 0; // Disable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'dhanniel21@gmail.com'; // SMTP username
        $mail->Password = 'cojuarmehaaixuql'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable TLS encryption
        $mail->Port = 465; // TCP port to connect to

        // Recipients
        $mail->setFrom('dhanniel21@gmail.com', 'Mercy Memorial Hospital');
        $mail->addAddress($to); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        return true; // Email successfully sent
    } catch (Exception $e) {
        error_log("Mail Error: " . $mail->ErrorInfo);
        error_log("Exception Message: " . $e->getMessage());
        error_log("Exception Code: " . $e->getCode());
        error_log("Exception Trace: " . $e->getTraceAsString());
        return false; // Failed to send email
    }
}
