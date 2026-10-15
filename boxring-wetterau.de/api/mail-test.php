<?php
declare(strict_types=1);

/**
 * Admin-Test des Mailversands: schickt EINE Testmail ausschliesslich an
 * ahmet@ozcorapci.de (fest im Code, nicht per Eingabe aenderbar) und gibt
 * den kompletten SMTP-Dialog zurueck. Keine Mail an Kassenwart oder Trainer.
 * Nur mit Admin-Login (wie trainer-liste.php), nur per POST.
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/AdminSession.php';
require_once __DIR__ . '/lib/Mailer.php';
require_once __DIR__ . '/lib/JsonResponse.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    error_log('mail-test.php: config.php fehlt');
    JsonResponse::send(500, ['success' => false, 'message' => 'Der Server ist noch nicht vollstaendig eingerichtet.']);
}
require_once $configPath;

AdminSession::start();
if (!AdminSession::isLoggedIn()) {
    JsonResponse::send(401, ['success' => false, 'message' => 'Nicht eingeloggt.']);
}
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    JsonResponse::send(405, ['success' => false, 'message' => 'Methode nicht erlaubt.']);
}

const MAIL_TEST_EMPFAENGER = 'ahmet@ozcorapci.de';

$protokoll = '';
$jetzt = (new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin')))->format('d.m.Y H:i:s');

try {
    Mailer::send(
        MAIL_TEST_EMPFAENGER,
        'Ahmet',
        'TEST Mailversand Trainer-Zeiterfassung ' . $jetzt,
        '<p>Das ist eine Testmail der Trainer-Zeiterfassung (' . htmlspecialchars($jetzt, ENT_QUOTES, 'UTF-8') . ').</p><p>Es ist keine Abrechnung und braucht keine Aktion.</p>',
        null,
        [],
        [],
        SMTP_USER, // wie die echte Abrechnung: Absender = Login-Postfach
        null,
        null,
        null,
        static function (string $zeile) use (&$protokoll): void {
            $protokoll .= $zeile;
        }
    );
} catch (Throwable $e) {
    error_log('mail-test.php: Versand fehlgeschlagen: ' . $e->getMessage());
    JsonResponse::send(500, [
        'success' => false,
        'message' => $e->getMessage(),
        'protokoll' => $protokoll,
    ]);
}

// Passwort (AUTH-Zeilen sind base64) nicht in die Ausgabe uebernehmen.
$protokoll = preg_replace('/^(.*(?:CLIENT -> SERVER: ))([A-Za-z0-9+\/=]{8,})\s*$/m', '$1[ausgeblendet]', $protokoll) ?? $protokoll;

JsonResponse::send(200, [
    'success' => true,
    'empfaenger' => MAIL_TEST_EMPFAENGER,
    'absender' => SMTP_USER,
    'smtpHost' => SMTP_HOST,
    'protokoll' => $protokoll,
]);
