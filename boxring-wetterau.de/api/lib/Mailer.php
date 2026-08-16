<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/phpmailer/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

final class Mailer
{
    /**
     * @param array{name:string,content:string,filename:string}|null $attachment
     * @param string[] $cc
     */
    public static function send(
        string $toEmail,
        string $toName,
        string $subject,
        string $bodyHtml,
        ?array $attachment = null,
        array $cc = []
    ): void {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->Port = SMTP_PORT;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = SMTP_SECURE; // 'tls' oder 'ssl'
            $mail->CharSet = 'UTF-8';

            $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            $mail->addAddress($toEmail, $toName);
            foreach ($cc as $ccEmail) {
                $mail->addCC($ccEmail);
            }
            $mail->addReplyTo(NOTIFY_EMAIL, 'Boxring Wetterau 1983 e.V.');

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $bodyHtml;
            $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $bodyHtml));

            if ($attachment !== null) {
                $mail->addStringAttachment($attachment['content'], $attachment['filename'], 'base64', 'application/pdf');
            }

            $mail->send();
        } catch (PHPMailerException $e) {
            throw new RuntimeException('E-Mail-Versand fehlgeschlagen: ' . $mail->ErrorInfo, 0, $e);
        }
    }
}
