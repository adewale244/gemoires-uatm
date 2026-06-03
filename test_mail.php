<?php
require_once 'vendor/autoload.php';
require_once 'config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host       = MAIL_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = MAIL_USERNAME;
    $mail->Password   = MAIL_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = MAIL_PORT;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom(MAIL_FROM, MAIL_NAME);
    $mail->addAddress(MAIL_FROM); // S'envoie à toi-même

    $mail->isHTML(true);
    $mail->Subject = 'Test GéMoires UATM';
    $mail->Body    = '<h1>Test email GéMoires ✅</h1><p>Si tu vois ça, les emails fonctionnent !</p>';

    $mail->send();
    echo '✅ Email envoyé avec succès !';

} catch (Exception $e) {
    echo '❌ Erreur : ' . $mail->ErrorInfo;
}