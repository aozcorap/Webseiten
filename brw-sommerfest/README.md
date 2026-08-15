# Sommerfest 2026 – Teilnahmeformular

Eigenständige, responsive Anmeldeseite (`index.html`) für das Vereins-Sommerfest
des Boxring Wetterau e.V. (Samstag, 05.09.2026, ab 16 Uhr, Brüder-Grimm-Weg,
61169 Friedberg-Dorheim). Inhalte, Adresse und Formularfelder ursprünglich vom
offiziellen gedruckten Teilnahmeformular übernommen; Farben/Layout an den
Sommerfest-Flyer angelehnt (warmes Creme, Orange-Rot-Verlauf) statt am
dunklen Farbschema der Vereinsseite – wirkt einladender für ein Sommerfest.

Live auf GitHub Pages: https://aozcorap.github.io/Webseiten/brw-sommerfest/

## Anmeldeseite (`index.html`)

- Formularfelder: Name des Mitglieds, Teilnahme-Checkbox, Anzahl Personen
  (inkl. Mitglied), Kinder unter 12, vegetarische Verpflegung (+ Anzahl),
  Mitgebrachtes (Salat/Kuchen, Mehrfachauswahl möglich), optionale
  Anmerkung. Kein E-Mail-Feld (bewusst entfernt).
- Kostenbeitrag: 5,00 € Pfand pro angemeldeter Person, wird live berechnet
  und über einen echten PayPal-Zahlungslink (PayPal Website Payments
  Standard, `business=ahmet@ozcorapci.de`) beglichen – öffnet die von
  PayPal selbst gehostete Bezahlseite, kein eigenes Backend nötig. Wird bei
  tatsächlicher Teilnahme zurückerstattet.
- Wegbeschreibung-Sektion (Adresse, Anfahrt Auto/ÖPNV, Google-Maps-Embed).
- Formular öffnet beim Absenden zusätzlich das E-Mail-Programm (`mailto:`)
  mit den eingegebenen Daten als Nachricht – die Anmeldung geht strukturiert
  per E-Mail an ahmet@ozcorapci.de.
- Eigene Kopien von Logo/Fonts unter `assets/`, damit der Ordner
  unabhängig vom Rest des Repos auf GitHub Pages funktioniert.
- Wichtig: Veranstaltungsort Sommerfest (Brüder-Grimm-Weg, Friedberg-Dorheim)
  ist NICHT identisch mit der Trainingshalle (Maria-Montessori-Weg 2,
  Friedberg).

## Admin-Übersicht

Passwortgeschütztes Overlay direkt auf der Hauptseite (`index.html`),
erreichbar über den Link "Admin" im Footer – kein separater
Seitenaufruf mehr. Zeigt eine Liste aller Anmeldungen (Name, Personen,
Kinder, Vegetarisch, Salat/Kuchen, Pfand-Status, Anmerkung) plus
Kennzahlen (Anzahl Anmeldungen, Personen gesamt, wie viele Salat/Kuchen
mitbringen).

- Zugang: Benutzername `vorstand`, Passwort `brw-vorstand`.
- **Wichtig:** Reiner clientseitiger Zugangsschutz (Passwort steht im
  Seitenquelltext) – kein Hochsicherheits-Login, aber ausreichend für ein
  internes Vereins-Tool ohne sensible Zahlungsdaten dahinter.
- Die Anmeldungsliste ist ein statisches JavaScript-Array im Seitencode
  (`ANMELDUNGEN` in `index.html`), da die Seite rein statisch ist (kein
  Server, keine Datenbank). Pfand-Status (bar oder PayPal bezahlt, Betrag)
  wird pro Anmeldung mitgeführt. Es gibt keine automatische Live-Anbindung
  an eingehende E-Mails – Claude aktualisiert die Liste bei Bedarf und
  deployt die Seite neu. Bei Bedarf einfach nachfragen ("Adminliste
  aktualisieren").
