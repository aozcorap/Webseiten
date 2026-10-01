# Trainerabrechnung – wo steht was?

Schnelle Orientierung für die Trainer-Zeiterfassung und -Abrechnung
(`trainer-zeiterfassung.html`). Für die vollständige Einrichtung siehe
`SETUP.md`, Abschnitt 7 – dieses Dokument beantwortet nur: **welche Datei
enthält welche Information, und wo ändere ich was.**

## 1. Die drei Arten von Information

| Was | Steht wo | Datei im Git-Repo? |
|---|---|---|
| Wer ist Trainer, welche Rolle (Satz), Login-Daten | `api/data/trainers.json` | **Nein** – echte Personendaten, liegt nur auf dem Server |
| Erfasste Arbeitsstunden pro Tag | `api/data/stunden.json` | **Nein** – liegt nur auf dem Server |
| Bereits abgerechnete Monate (Sperre gegen Doppelzahlung) | `api/data/abrechnungen.json` | **Nein** – liegt nur auf dem Server |
| Stundensätze in Euro, wer die PDF-Rechnung bekommt, Mail-Einstellungen | `api/config.php` | **Nein** – enthält Zugangsdaten, liegt nur auf dem Server |
| Vorlage/Dokumentation, welche Einstellungen es gibt | `api/config.sample.php` | Ja – Vorlage ohne echte Werte, zum Nachlesen |
| Die eigentliche Programmlogik (wer rechnet wie ab) | `api/trainer-*.php`, `api/lib/*.php` | Ja |

**Warum getrennt?** `config.php` und die drei `data/*.json`-Dateien enthalten
Passwörter, E-Mail-Adressen und Gehaltsdaten – die dürfen nie in einem
öffentlich einsehbaren Code-Repository landen. `config.sample.php` ist die
Vorlage davon (gleiche Struktur, Platzhalter-Werte), damit man trotzdem im
Repo nachlesen kann, welche Einstellungen es überhaupt gibt.

## 2. "Ich will wissen/ändern …" – Cheat Sheet

| Frage | Antwort |
|---|---|
| Welchen Stundensatz hat Trainer X? | Admin-Login auf `trainer-zeiterfassung.html` → Trainer-Übersicht. Steht direkt neben dem Namen. |
| Stundensatz von Trainer X ändern (Aushilfs- ↔ Haupttrainer) | Gleiche Übersicht, Button "Als … einstufen" bei dem Trainer. Ändert `rolle` in `trainers.json`. |
| Wie hoch ist der Euro-Betrag pro Satz? | `api/config.php`, Zeilen `TRAINER_STUNDENSATZ_AUSHILFSTRAINER` / `TRAINER_STUNDENSATZ_HAUPTTRAINER`. Vorlage mit Erklärung: `api/config.sample.php`. |
| Wer bekommt die PDF-Rechnung statt der einfachen Mail? | `api/config.php`, Zeile `HAUPTTRAINER_EMAIL` – genau eine Adresse, unabhängig vom Stundensatz. |
| Wie wird abgerechnet (Formel, Ablauf)? | `api/trainer-abrechnen.php`, Funktion `stundensatzFuer()` wählt den Satz nach Monat + Rolle; danach `Stunden × Satz = Betrag`. |
| Alle Trainer-Namen/E-Mails sehen | Admin-Login → Trainer-Übersicht. Technisch: `api/data/trainers.json` auf dem Server. |

## 3. Ablauf einer Abrechnung in 3 Schritten

1. **Trainer trägt Stunden ein** → `api/trainer-stunden.php` schreibt in `trainers data/stunden.json`.
2. **Trainer klickt "Abrechnen" für den Vormonat** → `api/trainer-abrechnen.php`:
   - liest die Stunden des Monats,
   - ermittelt den Satz über `stundensatzFuer($monat, $rolle)` (Satz hängt vom *abgerechneten* Monat ab, nicht vom heutigen Datum),
   - rechnet `Stunden × Satz = Betrag`,
   - verschickt die Mail – als PDF-Rechnung nur an `HAUPTTRAINER_EMAIL`, sonst als einfache Text-Mail,
   - trägt den Monat als "abgerechnet" in `abrechnungen.json` ein (verhindert doppelte Abrechnung).
3. **Kassenwart bekommt die Mail/Rechnung** und überweist.

## 4. Stand der Stundensätze (Oktober 2026)

| Rolle | Satz | Betrifft aktuell |
|---|---|---|
| Haupttrainer | 22,50 €/Std. (brutto) | Ahmet (+ PDF-Rechnung), Dennis Duric, Halim, Mansur |
| Aushilfstrainer | 15,- €/Std. | Joshua Kocks, Magamed Mezhidov |

Diese Tabelle ist eine Momentaufnahme zur Orientierung – die tatsächlich
gültige Zuordnung steht immer in der Admin-Übersicht, nicht hier (falls sich
mal was ändert und diese Datei nicht sofort aktualisiert wird).
