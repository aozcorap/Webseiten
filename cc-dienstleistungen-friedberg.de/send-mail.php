<?php
declare(strict_types=1);

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

$headers = "From: Website Kontaktformular <no-reply@cc-dienstleistungen-friedberg.de>\r\n"
    . "Reply-To: {$email}\r\n"
    . "Content-Type: text/plain; charset=UTF-8";

$erfolg = mail($empfaenger, $betreff, $text, $headers);

if (!$erfolg && isset($_GET['debug'])) {
    $fehler = error_get_last();
    file_put_contents(__DIR__ . '/mail-debug.log',
        date('c') . ' sendmail_path=' . ini_get('sendmail_path')
        . ' letzter_fehler=' . json_encode($fehler) . "\n",
        FILE_APPEND);
}

header('Location: /?formular=' . ($erfolg ? 'danke' : 'fehler') . '#kontakt');
exit;
