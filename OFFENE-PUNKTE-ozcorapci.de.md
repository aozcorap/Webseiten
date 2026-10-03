# Offene Punkte – ozcorapci.de (Stand 2026-10-03)

## Status der Gestaltungen

Es gibt drei Stände nebeneinander. **Nur die Hauptseite ist die Live-Seite.**
Die Entwürfe liegen unter `/entwuerfe/` (alle `noindex, nofollow`, aber ohne
Passwortschutz, also für jeden mit Link erreichbar).

| Stand | Ordner | URL (GitHub Pages) | Gestaltung |
|---|---|---|---|
| **Hauptseite (live)** | `ozcorapci.de/` | `…/ozcorapci.de/` | Schwarz/Gelb, DM Sans, Aufbau nach Consultant-Template |
| Original (gesichert) | `ozcorapci.de/entwuerfe/original/` | `…/entwuerfe/original/` | Kopie der Hauptseite zum Vergleichen |
| **Entwurf A** | `ozcorapci.de/entwuerfe/a/` | `…/entwuerfe/a/` | Petrol/Messing, Instrument Serif + Inter, neue Buttons und Icons |

Basis-URL: `https://aozcorap.github.io/Webseiten`

### Entwurf A wird höchstwahrscheinlich die neue Hauptseite

Entscheidung des Inhabers (2026-10-03): Entwurf A **nicht** jetzt übernehmen,
aber als Kandidaten für die Hauptseite festhalten.

- Gleicher Aufbau und gleiche Inhalte wie die Hauptseite, nur andere
  Gestaltung (dunkles Petrol, Messing als einziger Akzent, Serifenüberschriften,
  Hero mit weich auslaufendem Porträt, Skill-Icons wie im Original).
- Rückmeldung des Inhabers: gefällt "immer besser". Hero-Übergang, Schriftgrößen
  und Icons wurden nach Wunsch angepasst.
- Die Entwürfe B (Tinte & Kupfer) und C (Weiß & Marine) wurden verworfen und
  entfernt (nur noch in der Git-Historie).

**Beim Übernehmen von A auf die Hauptseite zu beachten:**
- Dateien `entwuerfe/a/index.html`, `styles.css`, `main.js` auf die Hauptseite
  verschieben, Pfade anpassen (A nutzt `../../assets/...`, die Hauptseite
  `assets/...`; Impressum/Datenschutz liegen auf Hauptseitenebene) und
  Impressum/Datenschutz auf die neue Gestaltung bringen.
- `styles.css?v=` erhöhen (GitHub Pages cached CSS 10 Minuten, sonst sehen
  Besucher alte Versionen).
- Danach `entwuerfe/original/` und `entwuerfe/a/` aus dem Repo entfernen (siehe
  nächster Abschnitt).

## Offen
- [ ] **Entscheidung:** Entwurf A auf die Hauptseite übernehmen (Inhaber
      entscheidet, Zeitpunkt offen).
- [ ] **IP-Risiko:** Die Struktur (Abschnittsfolge, Maße, Bildrahmen mit
      Versatzblock und Punktraster, Unterschrift-Block) lehnt sich eng an das
      Webflow-"Consultant"-Template (BRIX Templates) an. Farben, Schriften, Icons
      und Bilder sind eigenständig, die Struktur nicht. Entweder Template-Lizenz
      klären oder die Struktur vor dem Go-Live eigenständiger machen. Keine
      Rechtsberatung, ein Anwalt kann das verbindlich einschätzen.
- [ ] `entwuerfe/original/` ist die template-nahe Version und öffentlich
      erreichbar. Nach der Entscheidung löschen.
- [ ] Bilder sind Stockfotos (Unsplash) bzw. eine Montage (Gantt auf Laptop),
      siehe `ozcorapci.de/assets/img/BILDNACHWEISE.md`. Eigene Fotos
      (z. B. bei der Arbeit) würden die Wirkung steigern.
- [ ] **Inhalte vom Inhaber bestätigen:** dormakaba-Zeitraum im CV steht
      09/2020 – 08/2026 (Stelle damit beendet?); Status des KI-Telefonassistenten
      (läuft schon oder in Arbeit?); Zweck der Rack-Module; Bezugsgröße der
      Zeitersparnisse (45→5 Min., 90→5 Min.: pro Rechnung/Monat?).
- [ ] CV-Download (`assets/docs/CV_Ahmet_Oezcorapci.pdf`) enthält Telefonnummer
      und Anschrift. Bewusst entscheiden, ob das öffentlich sein soll.
- [ ] Nur in Bildschirmgrößen 390px/360px im Rahmen getestet, nicht auf einem
      echten Gerät. Einmal am Handy prüfen.
- [ ] Entwurf A nutzt drei Schriften (Instrument Serif, Inter, Mrs Saint
      Delafield für die Unterschrift). Die Projektregel erlaubt höchstens zwei.
      Bewusste Ausnahme wegen der Unterschrift.

## Hintergrund-Infos
- Schriften sind selbst gehostet (`ozcorapci.de/assets/fonts/`), keine
  Verbindung zu Google. Datenschutzerklärung enthält dazu einen Abschnitt.
- GitHub Pages cached CSS/HTML kurz im Browser (`max-age=600`). Nach
  Änderungen an Stylesheets die Versionsnummer im Link erhöhen, sonst zeigt
  der Browser den alten Stand. Zum Testen hilft ein Anhang wie `?neu=1`.
- Deployment: Push auf `master` startet `.github/workflows/pages.yml`, nach
  etwa einer Minute ist die Seite live. Unterordner mit `index.html` bekommen
  automatisch eine eigene URL.
