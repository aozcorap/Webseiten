<?php
declare(strict_types=1);

/**
 * Wird per Link aus der Registrierungs-Mail (trainer-register.php) direkt im
 * Browser des Vorstands/Kassenwarts geoeffnet - bewusst ohne eigenen Login,
 * das einmalige lange Zufalls-Token im Link ist der Zugriffsschutz.
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
        . 'h1{font-size:22px;margin:0 0 16px;}p{font-size:15px;color:#c4bdb5;line-height:1.6;}a{color:#e8394f;}</style></head><body>'
        . '<div class="card"><h1>' . htmlspecialchars($titel, ENT_QUOTES, 'UTF-8') . '</h1><p>' . $text . '</p></div>'
        . '</body></html>';
    exit;
}

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    seite('Fehler', 'Der Server ist noch nicht vollstaendig eingerichtet.');
}
require_once $configPath;

$token = isset($_GET['token']) && is_string($_GET['token']) ? $_GET['token'] : '';
$aktion = isset($_GET['aktion']) && is_string($_GET['aktion']) ? $_GET['aktion'] : '';

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

$neuerStatus = $aktion === 'genehmigen' ? 'aktiv' : 'abgelehnt';
TrainerStore::setTrainerStatus($trainer['id'], $neuerStatus);

$name = $trainer['vorname'] . ' ' . $trainer['nachname'];

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
