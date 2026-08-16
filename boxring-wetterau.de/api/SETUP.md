# Setup: Online-Mitgliedsanmeldung

Diese Anleitung ist für die einmalige Einrichtung nach dem Hochladen der neuen
Dateien (`mitglied-werden.html`, `api/`) auf den Server. Ohne diese Schritte
gibt das Formular einen Fehler zurück ("Server ist noch nicht vollständig
eingerichtet").

## 1. Voraussetzungen auf dem Server prüfen

- PHP 8.0 oder neuer, mit den Erweiterungen `curl`, `openssl`, `zlib`, `iconv`
  (auf Plesk-Hosting praktisch immer vorhanden – im Zweifel im Plesk-Panel
  unter "PHP-Einstellungen" nachsehen).
- Die Ordner `api/vendor/` (FPDF + PHPMailer) werden mitgeliefert – dafür ist
  **keine** Composer-Installation auf dem Server nötig.

## 2. Google Sheet für die Mitgliederliste (bereits angelegt)

Das Sheet "Boxring Wetterau – Mitgliederliste" ist die **eine gemeinsame
Quelle** für Bestandsmitglieder und neue Online-Anmeldungen. Es wurde aus der
Excel-Liste des Kassenwarts (`BRW Mitgliederliste.xlsx`) mit den 248 aktuell
geführten Mitgliedern befüllt, Kopfzeile: gekündigt Jahresende, Status,
Vorname, Nachname, IBAN, Beitrag, Mitgliedsnr, Mandatsref, Zahlungspflichtiger,
Straße, PLZ, Ort, Beruf, Telefon, Mail, Geburtstag, Eintritt, Anmeldegebühr
Zahldatum:

`https://docs.google.com/spreadsheets/d/1D2jkKifKdj9eokaSQAUoj4oGYSJ0pU2droyfxu-SUJs/edit`

Die Sheet-ID ist bereits in `config.sample.php` eingetragen. Neue
Online-Anmeldungen werden von `anmeldung.php` als neue Zeile mit exakt dieser
Spaltenreihenfolge angehängt (Mitgliedsnr/Mandatsref/Anmeldegebühr-Zahldatum
bleiben leer, die vergibt/pflegt der Kassenwart weiterhin von Hand).

**Hinweis zur Vollständigkeit:** Aus der Original-Excel wurden nur die für
den laufenden Betrieb nötigen Spalten übernommen. Die detaillierte
Jahres-Zahlungshistorie 2014–2025 (wer wann welchen Beitrag überwiesen hat)
steht weiterhin vollständig in der Original-Excel-Datei im Drive des Vereins
– die wurde nicht verändert oder gelöscht, dient aber nicht mehr als
Live-Arbeitsdokument.

**Freigabe für den Kassenwart:** Die automatische Freigabe per Skript ist an
einem Tool-Fehler gescheitert (Google Drive API lehnte den Share-Request ab).
Bitte das Sheet einmal manuell freigeben: oben rechts auf "Teilen" klicken,
`Kassenwart@boxring-wetterau.de` eintragen, Rolle "Bearbeiter" wählen.

## 3. Apps Script im Sheet einrichten (ca. 3 Minuten, kein Google-Cloud-Projekt nötig)

Statt eines separaten Google-Cloud-Projekts mit Service-Account nutzen wir ein
kleines Skript, das direkt im Sheet lebt und automatisch Schreibrechte auf
genau dieses eine Sheet hat:

1. Das Sheet aus Schritt 2 öffnen.
2. Menü *Erweiterungen → Apps Script*.
3. Den kompletten Inhalt von `api/apps-script/mitgliederliste.gs` (aus diesem
   Repo) kopieren und den Beispielcode im Editor damit ersetzen.
4. In der Zeile `var SHARED_SECRET = ...` das Platzhalter-Passwort durch ein
   eigenes, langes Zufallspasswort ersetzen (z. B. mit einem
   Passwort-Generator erzeugen, mindestens 32 Zeichen).
5. Oben rechts *Bereitstellen → Neue Bereitstellung*:
   - Typ: **Web-App**
   - Ausführen als: **Ich** (dein Google-Konto)
   - Zugriff: **Jeder** (das Secret aus Schritt 4 schützt den Endpunkt vor
     Fremdzugriff)
6. Google fragt nach Berechtigungen ("Diese App wurde nicht überprüft") – das
   ist normal für ein selbst geschriebenes Skript, auf "Erweitert" → "Zu
   [Projektname] wechseln (unsicher)" klicken und bestätigen.
7. Die angezeigte Web-App-URL kopieren (endet auf `.../exec`).

Bei einer späteren Änderung des Skripts: erneut *Bereitstellen → Bereitstellungen
verwalten* → Stift-Symbol → neue Version wählen → *Bereitstellen* (die URL
bleibt dabei gleich).

## 4. Dateien auf dem Server ablegen

- `api/config.sample.php` zu `api/config.php` kopieren (im selben Ordner) und
  ausfüllen:
  - `GOOGLE_SHEETS_WEBAPP_URL` → die Web-App-URL aus Schritt 3.7.
  - `GOOGLE_SHEETS_WEBAPP_SECRET` → exakt dasselbe Passwort wie `SHARED_SECRET`
    im Apps Script aus Schritt 3.4.
  - SMTP-Zugangsdaten des Vereins-Postfachs (`SMTP_HOST`, `SMTP_PORT`,
    `SMTP_USER`, `SMTP_PASS`, `SMTP_SECURE`) – im Zweifel beim
    E-Mail-Hosting/Plesk-Postfach-Einstellungen nachsehen (übliche Werte:
    Port 587 + `tls`, oder Port 465 + `ssl`).
  - `NOTIFY_EMAIL` → E-Mail-Adresse, die bei jeder neuen Anmeldung eine Kopie
    bekommt (z. B. Kassenwart@boxring-wetterau.de).
  - `MEMBER_CC_EMAIL` → **Go-Live-Schalter.** Steht auf CC der Willkommensmail
    ans neue Mitglied und im "Bei Fragen"-Text derselben Mail. In der
    Testphase auf `Kontakt@boxring-wetterau.de` gesetzt. Sobald live
    geschaltet wird: hier einfach `define('MEMBER_CC_EMAIL', NOTIFY_EMAIL);`
    eintragen (oder direkt die gewünschte Adresse) - das genügt, CC und
    Mailtext ziehen automatisch nach.

  **Wichtig:** `config.php` enthält Passwörter und darf **nicht** ins
  Git-Repo eingecheckt werden (steht bereits in `.gitignore`). Diese Datei
  nur direkt auf dem Server anlegen/hochladen.

## 5. Testen

1. `https://www.boxring-wetterau.de/mitglied-werden.html` aufrufen, Formular
   mit Testdaten ausfüllen (eine gültige Test-IBAN verwenden, z. B. die
   Vereins-IBAN selbst) und absenden.
2. Prüfen: Neue Zeile im Google Sheet? Willkommensmail mit PDF-Anhang beim
   Test-Empfänger angekommen, inkl. Kopie an `MEMBER_CC_EMAIL` als CC?
3. Bei Fehlern: Server-Error-Log prüfen (Plesk → Protokolle, oder
   `error_log`-Ausgabe je nach Hosting-Konfiguration) – das Skript schreibt
   dort verständliche Fehlermeldungen zu jedem der drei Schritte
   (Sheet/PDF/Mail).

## Hinweis zur Sicherheit

Selbst wenn ein Schritt fehlschlägt (z. B. Google Sheets nicht erreichbar),
versucht das Skript trotzdem, mindestens eine Benachrichtigung zu
verschicken, damit keine Anmeldung spurlos verloren geht. Im Zweifel lohnt
sich nach dem Livegang ein Blick ins Sheet UND ins Postfach, ob beide Wege
ankommen.
