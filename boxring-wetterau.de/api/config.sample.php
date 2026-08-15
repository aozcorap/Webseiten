<?php
declare(strict_types=1);

/**
 * Vorlage fuer api/config.php. Diese Datei nach config.php kopieren und mit
 * echten Zugangsdaten fuellen - config.php wird NICHT ins Git-Repo eingecheckt
 * (siehe .gitignore) und darf nur direkt auf dem Server angelegt werden.
 */

// --- SMTP: Versand ueber das bestehende Vereins-Postfach ---
define('SMTP_HOST', 'smtp.example.de');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls'); // 'tls' (Port meist 587) oder 'ssl' (Port meist 465)
define('SMTP_USER', 'info@boxring-wetterau.de');
define('SMTP_PASS', 'HIER-PASSWORT-EINTRAGEN');
define('SMTP_FROM_EMAIL', 'info@boxring-wetterau.de');
define('SMTP_FROM_NAME', 'Boxring Wetterau 1983 e.V.');

// Empfaenger fuer die interne Benachrichtigung bei jeder neuen Anmeldung
// (z.B. Kassenwart und/oder 1. Vorsitzender).
define('NOTIFY_EMAIL', 'Kassenwart@boxring-wetterau.de');
define('NOTIFY_NAME', 'Boxring Wetterau – Vereinskasse');

// --- Google Sheets: Speicherung der Anmeldungen ---
// Pfad zur Service-Account-JSON-Datei. UNBEDINGT ausserhalb des Webroots
// ablegen (z.B. eine Ebene ueber httpdocs/) oder per .htaccess sperren -
// die Datei enthaelt einen privaten Schluessel.
define('GOOGLE_SERVICE_ACCOUNT_JSON_PATH', __DIR__ . '/../../secrets/google-service-account.json');

// Die ID aus der Sheet-URL: https://docs.google.com/spreadsheets/d/<DIESE-ID>/edit
// Sheet "Boxring Wetterau – Mitgliedsanmeldungen" wurde bereits angelegt
// (inkl. Kopfzeile), diese ID ist bereits korrekt:
define('GOOGLE_SHEET_ID', '1EBY445YDsC49iHzlWD09MZQPuIyPUEbqPP5BKgxRRxU');

// Tabellenblatt-Name + Spaltenbereich, an den neue Zeilen angehaengt werden.
// Das automatisch angelegte Sheet hat standardmaessig ein Blatt "Sheet1" -
// im Zweifel im Sheet unten am Tab-Reiter den echten Namen pruefen.
define('GOOGLE_SHEET_RANGE', 'Sheet1!A:Z');
