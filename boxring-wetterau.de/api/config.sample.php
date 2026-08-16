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

// Reply-To fuer alle Vereins-Mails + Kontaktadresse im Fehlerfall.
define('NOTIFY_EMAIL', 'Kassenwart@boxring-wetterau.de');

// Basis-Adresse fuer MEMBER_CC_EMAIL (siehe unten).
define('CONTACT_EMAIL', 'Kontakt@boxring-wetterau.de');

// CC-Empfaenger + "Bei Fragen"-Kontaktadresse in der Willkommensmail ans neue
// Mitglied. EINZIGE Stelle, die beim Go-Live umgestellt werden muss: aktuell
// (Testphase) Kontakt@, danach auf NOTIFY_EMAIL (Kassenwart) aendern - wird
// dann automatisch an beiden Stellen (CC-Header + Mailtext) uebernommen,
// da im Code nur noch diese eine Konstante referenziert wird.
define('MEMBER_CC_EMAIL', CONTACT_EMAIL);

// --- Google Sheets: Speicherung der Anmeldungen (via Apps Script, siehe
// api/apps-script/mitgliederliste.gs - kein Google-Cloud-Projekt noetig) ---

// Die Web-App-URL, die man nach dem Bereitstellen des Apps Scripts bekommt
// (endet auf ".../exec"). Siehe Anleitung oben im .gs-File.
define('GOOGLE_SHEETS_WEBAPP_URL', 'HIER-APPS-SCRIPT-WEBAPP-URL-EINTRAGEN');

// Muss EXAKT mit SHARED_SECRET im Apps Script uebereinstimmen (dort selbst
// gesetzt). Schuetzt den Endpunkt, da die Web-App-URL oeffentlich erreichbar
// ist (Zugriff "Jeder").
define('GOOGLE_SHEETS_WEBAPP_SECRET', 'HIER-EIGENES-LANGES-ZUFALLSPASSWORT-EINTRAGEN');
