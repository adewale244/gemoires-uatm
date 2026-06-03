<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    public static function send($to, $toName, $subject, $body) {
        require_once 'config/mail.php';

        $mail = new PHPMailer(true);
        try {
           $mail->isSMTP();
$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(MAIL_FROM, MAIL_NAME);
            $mail->addAddress($to, $toName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = self::template($subject, $body);

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur mail : " . $mail->ErrorInfo);
            return false;
        }
    }

    private static function template($titre, $contenu) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
                .header { background: linear-gradient(135deg, #1B3A6B, #2d5aa0); padding: 30px; text-align: center; }
                .header h1 { color: #C8A84B; font-size: 28px; margin: 0; }
                .header p { color: #a0b8d8; font-size: 12px; margin: 5px 0 0; }
                .body { padding: 30px; }
                .body h2 { color: #1B3A6B; font-size: 18px; margin-bottom: 15px; }
                .body p { color: #555; line-height: 1.6; font-size: 14px; }
                .btn { display: inline-block; background: #1B3A6B; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: bold; margin-top: 20px; }
                .footer { background: #f8f9fc; padding: 20px; text-align: center; border-top: 1px solid #eee; }
                .footer p { color: #999; font-size: 12px; margin: 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>GéMoires</h1>
                    <p>UATM GASA Formation — Bibliothèque numérique académique</p>
                </div>
                <div class='body'>
                    <h2>$titre</h2>
                    <p>$contenu</p>
                    <a href='" . APP_URL . "' class='btn'>Accéder à GéMoires →</a>
                </div>
                <div class='footer'>
                    <p>© " . date('Y') . " GéMoires — UATM GASA Formation. Cet email est automatique.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}