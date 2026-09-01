<?php
declare(strict_types=1);

require __DIR__ . '/smtpmailer.php';
$smtpConfig = require __DIR__ . '/smtpconfig.php';

$empfaenger = 'serkan@gmail.info';

function feld(string $name): string {
    return trim((string)($_POST[$name] ?? ''));
}

function redirect_mit_fehler(string $grund): never {
    header('Location: /?formular=fehler#kontakt');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}

// Honeypot: unsichtbares Feld, das nur Bots ausfuellen
if (feld('website') !== '') {
    header('Location: /?formular=danke#kontakt');
    exit;
}

$name = feld('name');
$email = feld('email');
$telefon = feld('telefon');
$leistung = feld('leistung');
$nachricht = feld('nachricht');
$consent = feld('consent');

if ($name === '' || $email === '' || $consent === '') {
    redirect_mit_fehler('pflichtfelder');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_mit_fehler('email');
}

// Header-Injection ueber Zeilenumbrueche in Formularfeldern verhindern
$saeubern = static fn(string $s): string => str_replace(["\r", "\n"], ' ', $s);
$name = $saeubern($name);
$email = $saeubern($email);
$telefon = $saeubern($telefon);
$leistung = $saeubern($leistung);

$betreff = 'Neue Anfrage über die Website – ' . $name;

$text = "Neue Anfrage über das Kontaktformular\n\n"
    . "Name: {$name}\n"
    . "E-Mail: {$email}\n"
    . "Telefon: " . ($telefon !== '' ? $telefon : '–') . "\n"
    . "Leistung: " . ($leistung !== '' ? $leistung : '–') . "\n\n"
    . "Nachricht:\n{$nachricht}\n";

try {
    smtp_send($smtpConfig, $empfaenger, $betreff, $text, $email);
    $erfolg = true;
} catch (RuntimeException $e) {
    $erfolg = false;
    if (isset($_GET['debug'])) {
        file_put_contents(__DIR__ . '/mail-debug.log',
            date('c') . ' ' . $e->getMessage() . "\n",
            FILE_APPEND);
    }
}

header('Location: /?formular=' . ($erfolg ? 'danke' : 'fehler') . '#kontakt');
exit;
