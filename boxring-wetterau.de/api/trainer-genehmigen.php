<?php
declare(strict_types=1);

/**
 * Wird per Link aus der Registrierungs-Mail (trainer-register.php) direkt im
 * Browser des Vorstands/Kassenwarts geoeffnet - bewusst ohne eigenen Login,
 * das einmalige lange Zufalls-Token im Link ist der Zugriffsschutz.
 *
 * GET zeigt nur eine Bestaetigungsseite mit einem Button, der per POST
 * abschickt - erst der POST aendert den Status. Ein reiner GET-Link wuerde
 * sonst von automatischen Link-Scannern in Mail-Programmen (z.B. Outlook
 * Safe Links, Google Link-Proxy) ungewollt ausgeloest, bevor der
 * Kassenwart die Mail ueberhaupt liest.
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__ . '/lib/TrainerStore.php';
require_once __DIR__ . '/lib/Mailer.php';

function seite(string $titel, string $text): void
{
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">'
        . '<title>' . htmlspecialchars($titel, ENT_QUOTES, 'UTF-8') . ' – Boxring Wetterau 1983 e.V.</title>'
        . '<meta name="robots" content="noindex, nofollow">'
        . '<style>body{margin:0;background:#111110;color:#f2ede8;font-family:Inter,sans-serif;display:flex;min-height:100vh;align-items:center;justify-content:center;padding:24px;}'
        . '.card{background:#191817;border-radius:20px;border:1px solid rgba(242,237,232,0.06);padding:40px;max-width:480px;text-align:center;}'
        . 'h1{font-size:22px;margin:0 0 16px;}p{font-size:15px;color:#c4bdb5;line-height:1.6;}a{color:#e8394f;}'
        . 'button{font-family:inherit;font-size:15px;font-weight:700;letter-spacing:0.3px;border:none;border-radius:8px;padding:14px 28px;cursor:pointer;color:#fff;margin-top:8px;}'
        . '.btn-genehmigen{background:#2e9e4f;}.btn-ablehnen{background:#e8394f;}</style></head><body>'
        . '<div class="card"><h1>' . htmlspecialchars($titel, ENT_QUOTES, 'UTF-8') . '</h1><p>' . $text . '</p></div>'
        . '</body></html>';
    exit;
}

function bestaetigungsSeite(string $name, string $email, string $token, string $aktion): void
{
    $istGenehmigen = $aktion === 'genehmigen';
    $label = $istGenehmigen ? 'Trainer jetzt bestaetigen' : 'Registrierung jetzt ablehnen';
    $btnClass = $istGenehmigen ? 'btn-genehmigen' : 'btn-ablehnen';

    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">'
        . '<title>Trainer-Registrierung bestaetigen – Boxring Wetterau 1983 e.V.</title>'
        . '<meta name="robots" content="noindex, nofollow">'
        . '<style>body{margin:0;background:#111110;color:#f2ede8;font-family:Inter,sans-serif;display:flex;min-height:100vh;align-items:center;justify-content:center;padding:24px;}'
        . '.card{background:#191817;border-radius:20px;border:1px solid rgba(242,237,232,0.06);padding:40px;max-width:480px;text-align:center;}'
        . 'h1{font-size:22px;margin:0 0 16px;}p{font-size:15px;color:#c4bdb5;line-height:1.6;}'
        . 'button{width:100%;font-family:inherit;font-size:15px;font-weight:700;letter-spacing:0.3px;border:none;border-radius:8px;padding:14px 28px;cursor:pointer;color:#fff;margin-top:8px;}'
        . '.btn-genehmigen{background:#2e9e4f;}.btn-ablehnen{background:#e8394f;}</style></head><body>'
        . '<div class="card"><h1>Bitte bestaetigen</h1>'
        . '<p><strong>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</strong> (' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . ') hat sich fuer die Trainer-Zeiterfassung registriert.</p>'
        . '<form method="POST">'
        . '<input type="hidden" name="token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">'
        . '<input type="hidden" name="aktion" value="' . htmlspecialchars($aktion, ENT_QUOTES, 'UTF-8') . '">'
        . '<button type="submit" class="' . $btnClass . '">' . $label . '</button>'
        . '</form></div></body></html>';
    exit;
}

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    seite('Fehler', 'Der Server ist noch nicht vollstaendig eingerichtet.');
}
require_once $configPath;

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$token = $method === 'POST'
    ? (isset($_POST['token']) && is_string($_POST['token']) ? $_POST['token'] : '')
    : (isset($_GET['token']) && is_string($_GET['token']) ? $_GET['token'] : '');
$aktion = $method === 'POST'
    ? (isset($_POST['aktion']) && is_string($_POST['aktion']) ? $_POST['aktion'] : '')
    : (isset($_GET['aktion']) && is_string($_GET['aktion']) ? $_GET['aktion'] : '');

if ($token === '' || !in_array($aktion, ['genehmigen', 'ablehnen'], true)) {
    http_response_code(400);
    seite('Ungueltiger Link', 'Dieser Link ist nicht gueltig.');
}

$trainer = TrainerStore::findTrainerByApproveToken($token);
if ($trainer === null || $trainer['status'] !== 'pending') {
    seite('Link nicht mehr gueltig', 'Dieser Link wurde bereits verwendet oder ist abgelaufen. Falls noetig, den Trainer bitten, sich erneut zu registrieren.');
}

$tokenExpiry = new DateTimeImmutable($trainer['approveTokenExpiry']);
if ($tokenExpiry < new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin'))) {
    seite('Link abgelaufen', 'Dieser Link ist abgelaufen. Bitte den Trainer bitten, sich erneut zu registrieren.');
}

$name = $trainer['vorname'] . ' ' . $trainer['nachname'];

// GET zeigt nur die Bestaetigungsseite - noch keine Statusaenderung. Das
// verhindert, dass automatische Link-Vorschau/-Scanner in Mail-Programmen
// den Trainer ungewollt schon vor dem Lesen der Mail freischalten/ablehnen.
if ($method !== 'POST') {
    bestaetigungsSeite($name, $trainer['email'], $token, $aktion);
}

$neuerStatus = $aktion === 'genehmigen' ? 'aktiv' : 'abgelehnt';
TrainerStore::setTrainerStatus($trainer['id'], $neuerStatus);

try {
    $signatur = '<p>Sportliche Gruesse,<br>Boxring Wetterau 1983 e.V.</p>';

    if ($neuerStatus === 'aktiv') {
        Mailer::send(
            $trainer['email'],
            $name,
            'Dein Trainer-Zugang wurde freigeschaltet',
            '<p>Hallo ' . htmlspecialchars($trainer['vorname'], ENT_QUOTES, 'UTF-8') . ',</p>'
                . '<p>dein Account fuer die Trainer-Zeiterfassung wurde freigeschaltet. Du kannst dich jetzt einloggen und deine Trainingsstunden eintragen:</p>'
                . '<p><a href="https://www.boxring-wetterau.de/trainer-zeiterfassung.html">Zur Zeiterfassung</a></p>'
                . $signatur
        );
    } else {
        Mailer::send(
            $trainer['email'],
            $name,
            'Trainer-Zugang zur Zeiterfassung',
            '<p>Hallo ' . htmlspecialchars($trainer['vorname'], ENT_QUOTES, 'UTF-8') . ',</p>'
                . '<p>deine Registrierung fuer die Trainer-Zeiterfassung wurde leider nicht bestaetigt.</p>'
                . '<p>Bei Rueckfragen wende dich bitte an den Vorstand oder Kassenwart: <a href="mailto:' . NOTIFY_EMAIL . '">' . NOTIFY_EMAIL . '</a>.</p>'
                . $signatur
        );
    }
} catch (Throwable $e) {
    error_log('trainer-genehmigen.php: Mail an Trainer fehlgeschlagen: ' . $e->getMessage());
}

if ($neuerStatus === 'aktiv') {
    seite('Trainer bestaetigt', htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' wurde freigeschaltet und kann sich jetzt in der Trainer-Zeiterfassung einloggen.');
}
seite('Trainer abgelehnt', 'Die Registrierung von ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' wurde abgelehnt.');
