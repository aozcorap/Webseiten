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
- Nach jeder erfolgreichen Anmeldung schickt die Seite zusätzlich automatisch
  eine kurze Benachrichtigungsmail (Name, Personen, Vegetarisch, Salat/Kuchen,
  Pfand-Betrag) über `boxring-wetterau.de/api/sommerfestnotify.php` – denselben
  SMTP-Versand, der auch für die Mitgliedsanmeldung genutzt wird. Kein Reply-nötig,
  reine Info-Mail zum Nachtracking (z. B. wenn die PayPal-Zahlung technisch nicht
  durchgeht). Läuft "fire and forget" im Hintergrund; Supabase bleibt die
  eigentliche Datenquelle, ein Fehler beim Mailversand blockiert die Anmeldung
  nicht.
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
- Die Anmeldungen liegen in Supabase (Tabelle `anmeldungen`, Projekt
  „brw-sommerfest"). Das öffentliche Formular schreibt beim Absenden
  automatisch, der Admin-Bereich liest live (Auto-Refresh alle 15 Sek.).
- Im Admin-Bereich können manuell neue Anmeldungen hinzugefügt ("+
  Anmeldung hinzufügen") und bestehende gelöscht werden ("Löschen"-Button
  je Zeile, mit Bestätigungsabfrage) – z. B. für Barzahler, die sich
  persönlich angemeldet haben, oder um Dubletten zu entfernen.
- Pfand-Status pro Zeile per Dropdown änderbar (Offen / Bar bezahlt / PayPal
  bezahlt). Über das öffentliche Formular eingehende Anmeldungen starten
  bewusst als "Offen" (das Öffnen der PayPal-Seite ist keine
  Zahlungsbestätigung – die Zahlung kann technisch fehlschlagen). Der
  Vorstand stellt den Status nachträglich um, sobald das Pfand tatsächlich
  angekommen ist (bar oder per PayPal bestätigt); der Betrag (Personen × 5 €)
  wird dabei automatisch mitgesetzt bzw. beim Zurückstellen auf "Offen"
  wieder entfernt.
- **Sicherheitshinweis:** Die Supabase-Tabelle nutzt einen öffentlichen
  „publishable key" mit Row-Level-Security-Policies, die `INSERT`,
  `SELECT`, `UPDATE` und `DELETE` für jeden erlauben, der den Key kennt (er
  steht im Seitenquelltext, wie der öffentliche Zugangsschutz auch).
  Löschen/Ändern ist also nicht wirklich an das Admin-Passwort gekoppelt,
  sondern nur UI-seitig dahinter versteckt. Für ein internes Vereins-Tool
  ohne sensible Zahlungsdaten als Trade-off akzeptiert – bei Bedarf (z. B.
  bei Vandalismus) Claude ansprechen, um die Policies zu verschärfen.
