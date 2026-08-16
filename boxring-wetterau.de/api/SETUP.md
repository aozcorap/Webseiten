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

## 3. Google-Service-Account anlegen

1. In der [Google Cloud Console](https://console.cloud.google.com/) ein neues
   Projekt anlegen (z. B. "boxring-wetterau-anmeldung").
2. "Google Sheets API" für dieses Projekt aktivieren (Menü *APIs & Dienste* →
   *Bibliothek* → "Google Sheets API" → Aktivieren).
3. Unter *APIs & Dienste* → *Anmeldedaten* → *Anmeldedaten erstellen* →
   *Dienstkonto* ein neues Dienstkonto anlegen (Rolle kann leer bleiben).
4. Im Dienstkonto unter *Schlüssel* → *Schlüssel hinzufügen* → *Neuen Schlüssel
   erstellen* → **JSON** wählen. Die Datei wird heruntergeladen.
5. Die im JSON enthaltene `client_email` (sieht aus wie
   `xyz@projekt.iam.gserviceaccount.com`) kopieren.
6. Im Google Sheet aus Schritt 2 auf "Teilen" klicken und diese `client_email`
   als **Bearbeiter** hinzufügen (genau wie eine Person einladen).

## 4. Dateien auf dem Server ablegen

- Die heruntergeladene JSON-Datei **außerhalb** des Webroots ablegen, z. B.
  eine Ebene über `httpdocs/` in einem Ordner `secrets/` (Pfad ist bei Plesk
  meist `/var/www/vhosts/boxring-wetterau.de/secrets/google-service-account.json`,
  außerhalb von `httpdocs/`). Falls das nicht möglich ist: im Ordner `api/`
  ablegen – der ist bereits per `.htaccess` gegen `.json`-Zugriff von außen
  gesperrt, aber ein separater Ordner außerhalb des Webroots ist sicherer.
- `api/config.sample.php` zu `api/config.php` kopieren (im selben Ordner) und
  ausfüllen:
  - `GOOGLE_SERVICE_ACCOUNT_JSON_PATH` → Pfad zur JSON-Datei aus Schritt 4.
  - `GOOGLE_SHEET_ID` → ID aus Schritt 2.
  - `GOOGLE_SHEET_RANGE` → Tabellenblattname + Spaltenbereich, Standard ist
    bereits `Sheet1!A:R` (18 Spalten) passend zur Mitgliederliste.
  - SMTP-Zugangsdaten des Vereins-Postfachs (`SMTP_HOST`, `SMTP_PORT`,
    `SMTP_USER`, `SMTP_PASS`, `SMTP_SECURE`) – im Zweifel beim
    E-Mail-Hosting/Plesk-Postfach-Einstellungen nachsehen (übliche Werte:
    Port 587 + `tls`, oder Port 465 + `ssl`).
  - `NOTIFY_EMAIL` → E-Mail-Adresse, die bei jeder neuen Anmeldung eine Kopie
    bekommt (z. B. Kassenwart@boxring-wetterau.de).

  **Wichtig:** `config.php` enthält Passwörter und darf **nicht** ins
  Git-Repo eingecheckt werden (steht bereits in `.gitignore`). Diese Datei
  nur direkt auf dem Server anlegen/hochladen.

## 5. Testen

1. `https://www.boxring-wetterau.de/mitglied-werden.html` aufrufen, Formular
   mit Testdaten ausfüllen (eine gültige Test-IBAN verwenden, z. B. die
   Vereins-IBAN selbst) und absenden.
2. Prüfen: Neue Zeile im Google Sheet? Bestätigungsmail mit PDF-Anhang
   angekommen? Kopie an `NOTIFY_EMAIL` angekommen?
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
