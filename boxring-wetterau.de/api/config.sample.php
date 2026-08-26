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
// Absender der Willkommensmail ans neue Mitglied: der Kassenwart, da er
// fachlich zustaendig ist (Beitrag/SEPA-Einzug) und Rueckfragen direkt
// beantworten kann.
define('SMTP_FROM_EMAIL', 'Kassenwart@boxring-wetterau.de');
define('SMTP_FROM_NAME', 'Boxring Wetterau 1983 e.V.');

// Reply-To fuer alle Vereins-Mails + "Bei Fragen"-Kontaktadresse im
// Willkommensmailtext + Kontaktadresse im Fehlerfall.
define('NOTIFY_EMAIL', 'Kassenwart@boxring-wetterau.de');

// Basis-Adresse fuer MEMBER_CC_EMAIL (siehe unten).
define('CONTACT_EMAIL', 'Kontakt@boxring-wetterau.de');

// CC-Empfaenger der Willkommensmail ans neue Mitglied (zur Kenntnisnahme
// durch den Verein, unabhaengig vom Absender/"Bei Fragen"-Kontakt oben).
// Der Kassenwart (NOTIFY_EMAIL) wird in anmeldung.php zusaetzlich in CC
// gesetzt, damit er trotz Versands ueber SMTP_FROM_EMAIL eine eigene
// digitale Kopie des PDFs erhaelt.
define('MEMBER_CC_EMAIL', CONTACT_EMAIL);

// Einladungslink zur WhatsApp-Gruppe des Vereins (Gruppeninfo -> "Einladung
// per Link senden"). Wird in der Willkommensmail ans neue Mitglied verlinkt.
// Kein API-Schluessel noetig - WhatsApp erlaubt keinen automatischen Beitritt,
// nur den Klick auf einen Einladungslink durch das Mitglied selbst.
define('WHATSAPP_GROUP_INVITE_URL', 'https://chat.whatsapp.com/HIER-EINLADUNGSLINK-EINTRAGEN');

// --- Google Sheets: Speicherung der Anmeldungen (via Apps Script, siehe
// api/apps-script/mitgliederliste.gs - kein Google-Cloud-Projekt noetig) ---

// Die Web-App-URL, die man nach dem Bereitstellen des Apps Scripts bekommt
// (endet auf ".../exec"). Siehe Anleitung oben im .gs-File.
define('GOOGLE_SHEETS_WEBAPP_URL', 'https://script.google.com/macros/s/AKfycbzrR-ml8fjyAZhd5C4-fm2iljHoWoEVO9I4-IvLnZ7oRskA3M1xkFGM1sdKZSaqKgr0/exec');

// Muss EXAKT mit SHARED_SECRET im Apps Script uebereinstimmen (dort selbst
// gesetzt). Schuetzt den Endpunkt, da die Web-App-URL oeffentlich erreichbar
// ist (Zugriff "Jeder").
define('GOOGLE_SHEETS_WEBAPP_SECRET', 'RcY_ogqOfuZqECmOGqcDA-FC16eQnqkyGJ2AcU0rXHQ');

// --- Trainer-Adminbereich (mitglied-check.html): Mitglied-Check waehrend
// des Trainings, ob eine Person bereits online/schriftlich angemeldet ist ---

// Geteilter Benutzername + geteiltes Passwort fuer alle Trainer (keine
// einzelnen Nutzerkonten - fuer den Zweck unverhaeltnismaessig).
define('ADMIN_USERNAME', 'HIER-EIGENEN-BENUTZERNAMEN-EINTRAGEN');
define('ADMIN_PASSWORD', 'HIER-EIGENES-PASSWORT-EINTRAGEN');

// --- Trainer-Zeiterfassung (trainer-zeiterfassung.html): individuelle
// Trainer-Accounts, Stundenerfassung + monatliche Abrechnung per Mail an den
// Kassenwart (NOTIFY_EMAIL oben). Genehmigung neuer Trainer erfolgt per
// Bestaetigungslink in einer E-Mail an NOTIFY_EMAIL, kein separates Passwort
// noetig. ---

// Verguetung pro voller Trainingsstunde, gilt fuer alle Trainer gleich.
// Fuer HAUPTTRAINER_EMAIL (siehe unten) gilt dieser Satz als BRUTTO inkl.
// 19% MwSt. (er ist umsatzsteuerpflichtig, bekommt eine echte PDF-Rechnung
// statt einer einfachen Text-Mail) - bei allen anderen Trainern ist es ein
// einfacher Betrag ohne MwSt-Berechnung.
define('TRAINER_STUNDENSATZ', 20.0);

// --- PDF-Rechnung fuer den Haupttrainer (RechnungBuilder.php) ---
// E-Mail-Adresse des Haupttrainers - stimmt mit seinem Trainer-Account
// ueberein. Nur fuer diese eine Adresse wird beim Abrechnen eine echte
// PDF-Rechnung erzeugt (an den Kassenwart, CC an ihn selbst) statt der
// einfachen Text-Mail, die alle anderen Trainer bekommen.
define('HAUPTTRAINER_EMAIL', 'ahmet@ozcorapci.de');

define('HAUPTTRAINER_NAME', 'HIER-NAME-EINTRAGEN');
define('HAUPTTRAINER_STRASSE', 'HIER-STRASSE-HAUSNUMMER-EINTRAGEN');
define('HAUPTTRAINER_PLZ_ORT', 'HIER-PLZ-ORT-EINTRAGEN');
define('HAUPTTRAINER_ORT', 'HIER-NUR-ORT-EINTRAGEN'); // fuer "Ort, den TT. Monat JJJJ" auf der Rechnung

define('HAUPTTRAINER_BANKNAME', 'HIER-BANKNAME-EINTRAGEN');
define('HAUPTTRAINER_IBAN', 'HIER-IBAN-EINTRAGEN');
define('HAUPTTRAINER_BIC', 'HIER-BIC-EINTRAGEN');
define('HAUPTTRAINER_STEUERNUMMER', 'HIER-STEUERNUMMER-EINTRAGEN');

// Rechnungsempfaenger-Adressblock (Verein) - wie auf der Beispielrechnung.
define('VEREIN_RECHNUNGSNAME', 'Boxring Wetterau');
define('VEREIN_RECHNUNGSSTRASSE', 'Hospitalgasse 36');
define('VEREIN_RECHNUNGSORT', '61169 Friedberg');
