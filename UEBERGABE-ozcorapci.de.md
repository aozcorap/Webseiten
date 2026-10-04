# Übergabe: ozcorapci.de (Stand 2026-10-03, Ende der Session)

> **Überholt seit 2026-10-04:** Entwurf A ist die Hauptseite, die Ordner `entwuerfe/original/` und `entwuerfe/a/` sind gelöscht (nur in der Git-Historie), die Seite läuft unter https://ozcorapci.de aus dem Repository `aozcorap/ozcorapci.de` (GitHub Pages). Die Abschnitte unten beschreiben den Stand vom 2026-10-03.

Dieses Dokument reicht, um in einer neuen Session ohne Rückfragen
weiterzumachen. Zuerst lesen: dieses Dokument, dann `CLAUDE.md` (Projektregeln)
und `OFFENE-PUNKTE-ozcorapci.de.md` (Status und offene Punkte).

## 1. Kurzfassung

- Projekt: persönliche Website von Ahmet Özcorapci (Senior Program Manager,
  IT-Infrastruktur und KI-Automatisierung). Reines HTML/CSS/JS, kein Build,
  Ordner `ozcorapci.de/` im Repo `aozcorap/Webseiten`.
- Es gibt **drei Stände**: Hauptseite (live), `entwuerfe/original/` (Kopie der
  Hauptseite) und **`entwuerfe/a/` (Entwurf A, Petrol/Messing)**.
- **Entwurf A wird höchstwahrscheinlich die neue Hauptseite.** Der Inhaber hat
  entschieden: **noch nicht übernehmen**, nur dokumentieren (erledigt).
- Alles ist committed und auf `master` gemergt (PRs #145 bis #163). Es liegt
  nichts Ungesichertes lokal, außer den Dateien dieser Übergabe (siehe 9).

## 2. URLs (GitHub Pages, Deployment nach ca. 1 Minute)

Basis: `https://aozcorap.github.io/Webseiten`

| Was | URL |
|---|---|
| Hauptseite (live) | `…/ozcorapci.de/` |
| Original (gesichert) | `…/ozcorapci.de/entwuerfe/original/` |
| **Entwurf A** | `…/ozcorapci.de/entwuerfe/a/` |
| Übersicht der Entwürfe | `…/ozcorapci.de/entwuerfe/` |

Die Entwürfe sind `noindex, nofollow`, aber **nicht passwortgeschützt**.
Eine eigene Domain `ozcorapci.de` ist nicht Teil dieses Repos, die URLs oben
sind die GitHub-Pages-Adressen.

## 3. Entscheidungen des Inhabers (nicht erneut diskutieren)

- Entwurf A: gefällt, "immer besser". Petrol bleibt. Wirkung soll "teure
  Agentur, sehr seniorer Berater" sein.
- Entwürfe B (Tinte & Kupfer) und C (Weiß & Marine): verworfen, entfernt
  (nur noch in der Git-Historie).
- Aufbau von A = Aufbau der Hauptseite (Foto links im Hero, aufklappbare
  Stationen usw.). Nur Gestaltung (Farben, Buttons, Icons, Schrift) ist anders.
- Hero: Porträt **freigestellt**, Übergang zum schwarzen Jackett weich
  (erledigt über Maske und Hintergrundverlauf).
- Schriftgrößen von Kennzahlen und Abschnittsüberschriften bewusst klein
  (max. 50px bzw. 44px). Nicht wieder vergrößern.
- Skill-Icons: die Linien-Icons aus dem Original, ohne Kreis, mit Zoom beim Hover.
- Unterschrift im Hero: Schrift **Mrs Saint Delafield** (Hauptseite, Original
  und Entwurf A). Diese Schrift hat der Inhaber gewählt, nicht ändern.
- Bildleiste im Werdegang: links Hochhäuser, Mitte Meetingraum hinter Glas
  (Personen klein, keine erkennbaren Gesichter), rechts Laptop im Büro mit
  Gantt-Diagramm (Montage). Auf dem Handy nur das erste Bild.
- Auf dem Handy: Foto oben im Hero, Skills und KI-Projekte als wischbare
  Karten, Werdegang ab Vorwerk aufklappbar. Seite muss bei 390px und 360px
  ohne seitliches Scrollen laufen.
- **Nicht umbauen ohne Auftrag:** keine Einblend-Animation beim Scrollen
  (sie machte Inhalte in Screenshots unsichtbar).

## 4. Dateien und Aufbau

```
ozcorapci.de/
  index.html, impressum.html, datenschutz.html     Hauptseite (Schwarz/Gelb)
  css/styles.css, js/main.js
  assets/fonts/    dm-sans, inter, instrument-serif (+italic), mrs-saint-delafield
  assets/img/      profilbild-freigestellt.webp (Hero und Über mich),
                   profilbild.jpg (nicht mehr verwendet), strip-*.jpg,
                   projekt-dashboard.jpg, banner-skyline.jpg, BILDNACHWEISE.md
  assets/docs/CV_Ahmet_Oezcorapci.pdf              aktueller CV (Download)
  entwuerfe/index.html                             Übersichtsseite
  entwuerfe/original/ (index.html, css/, js/)      Kopie der Hauptseite
  entwuerfe/a/        (index.html, styles.css, main.js)   Entwurf A
quellen-ozcorapci.de/   Gantt-SVGs und Montage-Skript (nicht veröffentlicht)
OFFENE-PUNKTE-ozcorapci.de.md, UEBERGABE-ozcorapci.de.md   Doku
```

- Entwurf A liegt als eigenständige Dateien (`styles.css` mit eigenen
  Design-Token im `:root`). Er wird **direkt in den Dateien** weiterbearbeitet.
  Das Generator-Skript aus der Session (`build_a3.py`, `a3.css`) existiert nicht
  mehr und wird nicht gebraucht.
- Die Hauptseite und `entwuerfe/original/` haben dasselbe Markup, aber
  unterschiedliche Pfade (`entwuerfe/original/` nutzt `../../assets/...`).
  Änderungen an der Hauptseite bei Bedarf in beiden nachziehen.
- Entwurf A (Design-Token, Auswahl, alle in `entwuerfe/a/styles.css` im `:root`):
  Tinte `--ink #08262b`, Petrol `--petrol #0f4c54`, Messing `--brass #c9ad72`,
  Papier `--paper #f5f6f3`; Schriften Instrument Serif (Überschriften), Inter
  (Text), Mrs Saint Delafield (nur Unterschrift).
- Inhalte stammen aus dem aktuellen CV und von der früheren Seite. Neu
  formuliert sind nur Überschriften und die Hero-Zeile ("Programmleitung mit
  technischem Tiefgang", abgeleitet aus der früheren Meta-Description).

## 5. Offene Punkte und nächste Schritte (Reihenfolge nach Nutzen)

1. **Inhaber entscheidet:** Entwurf A auf die Hauptseite übernehmen. Schritte
   dafür stehen in `OFFENE-PUNKTE-ozcorapci.de.md`. Vorher nachfragen, nicht
   eigenmächtig tun.
2. **Inhalte vom Inhaber bestätigen lassen:**
   - dormakaba: CV sagt 09/2020 – 08/2026, auf der Seite steht 08/2026 statt
     "heute". Stelle beendet?
   - KI-Telefonassistent für einen Containerdienst: Status (läuft/in Arbeit)?
     Kundenname darf **nicht** genannt werden.
   - Eigene Rack-Module: wofür? Auf der Seite steht nur "Rack-Module selbst
     aufgebaut".
   - Zeitersparnisse (45→5 Min., 90→5 Min., ~90 %): Bezugsgröße (pro Rechnung,
     pro Monat)? Steht nirgends.
   - Trading-Bot steht nicht im CV, ist aber als Karte drin.
3. **IP-Risiko:** Struktur lehnt sich eng an das Webflow-Template "Consultant"
   (BRIX Templates) an: Abschnittsfolge, Maße, Bildrahmen mit Versatzblock und
   Punktraster, Unterschrift-Block. Farben, Schriften, Icons und Bilder sind
   eigenständig. Entweder Lizenz klären oder die Struktur eigenständiger machen.
   Keine Rechtsberatung. `entwuerfe/original/` ist die template-nahe Version und
   öffentlich erreichbar: nach der Entscheidung löschen.
4. **Datenschutz:** CV-PDF enthält Telefonnummer und Anschrift (Inhaber
   entscheiden lassen). Schriften sind selbst gehostet, Datenschutzerklärung
   hat dazu einen Abschnitt.
5. **Bilder:** alles Stockfotos (Unsplash) oder Montage, siehe
   `BILDNACHWEISE.md`. Eigene Fotos würden die Wirkung steigern. Das
   iStock-Foto, das der Inhaber als Vorbild nannte, wurde **nicht** genutzt
   (kostenpflichtig, nicht lizenziert).
6. **Tests:** nur in 390px/360px im Rahmen gerendert, nicht auf einem echten
   Handy. Echte Prüfung am Gerät steht aus. Auf dem Hintergrundmonitor im
   Gantt-Foto läuft noch eine unscharfe Webseite aus dem Originalfoto (kann
   auf Wunsch überdeckt werden).
7. Entwurf A nutzt **drei Schriften** (Projektregel: höchstens zwei). Bewusste
   Ausnahme wegen der Unterschrift. Wenn zwei gewünscht: Überschriften auf
   Inter, wirkt aber weniger edel.
8. Impressum und Datenschutz liegen nur im Schwarz/Gelb-Design vor (Footer
   mit Spalten wie die Hauptseite). Entwurf A verlinkt auf dieselben Seiten
   (`../../impressum.html`). Bei Übernahme von A beide Seiten ins Petrol-Design
   bringen.

## 6. Arbeitsweise und Fallstricke (wichtig)

**Zusammenarbeit mit dem Inhaber**
- Sprache Deutsch, kurze klare Antworten, die unbequeme Wahrheit zuerst,
  nichts erfinden, nachfragen. Keine Floskeln. Siehe seine Nutzereinstellungen.
- **Mehrdeutige Sätze erst klären.** Zwei teure Missverständnisse in dieser
  Session: "beide Versionen" hieß Hauptseite + Entwurf A (nicht die Entwürfe
  Original und A); "Original-Seite" = Hauptseite. Bei "beide/Original/Version"
  nachfragen, welche Stände gemeint sind.
- Der Inhaber will die Seite **gerendert sehen**, nicht nur Code. Immer
  Screenshots schicken (SendUserFile) und die Live-URL nennen.
- Bei Bildern: Optionen zeigen, bevor eingebaut wird. Keine Gesichter, wenn
  er das sagt. Er lehnt generische oder "KI-generierte" Wirkung ab.
- Er hat in dieser Session nach jedem Schritt "live sehen" gewollt. Das
  Muster war: Branch committen, PR erstellen, **sofort mergen** (er hat das
  ausdrücklich so gewollt), Deployment abwarten, per `curl` prüfen. Das nicht
  stillschweigend auf neue Themen ausweiten. Bei größeren oder riskanten
  Änderungen vorher fragen.

**Technik**
- Branch: `claude/lucid-ride-n7p88e`. Commit-Nachrichten enden mit den
  Attributionszeilen aus der Session (Co-Authored-By und Claude-Session).
  Vor jeder Änderung: `git fetch origin master && git checkout -B <branch>
  origin/master` (der Branch wird nach jedem Merge auf `master` neu aufgesetzt,
  Push mit `--force-with-lease`).
- GitHub nur über MCP-Tools (`mcp__github__*`), kein `gh`.
- **Cache:** GitHub Pages liefert CSS 10 Minuten (`max-age=600`). Nach jeder
  Änderung an einem Stylesheet oder Bild die Version im Link erhöhen
  (`styles.css?v=N`, `strip-projektplan.jpg?v=2`). Das war dreimal die Ursache
  für "sieht noch alt aus". Aktuell: Hauptseite, Original, Impressum und Datenschutz `?v=14`,
  Entwurf A `?v=8` (Stand dieser Übergabe, immer um eins erhöhen). Zum Prüfen im Browser `?neu=1` an die URL hängen.
- **Screenshots:** Chromium liegt in `/opt/pw-browsers/chromium`. Beispiel:
  `chromium --headless --no-sandbox --disable-gpu --hide-scrollbars
  --force-prefers-reduced-motion --virtual-time-budget=5000
  --window-size=1440,950 --screenshot=out.png http://localhost:PORT/index.html`.
  Lokale Seite mit `python3 -m http.server PORT --directory
  ozcorapci.de`. Hero-Animation lief in Screenshots mittendrin: mit
  `--force-prefers-reduced-motion` aufnehmen.
- **Handy testen:** Chromium rendert nicht unter ca. 500px Fensterbreite. Seite
  in einen `<iframe>` mit `width:390px` laden und dort `scrollWidth` messen.
  Das Prüfen auf seitliches Scrollen so automatisieren.
- **Live-URLs rendern:** Chromium vertraut dem Proxy-Zertifikat nicht und
  verweigert HTTPS. Dateien per `curl` laden (der Proxy ist für curl
  vorkonfiguriert) und lokal rendern. TLS-Prüfung nicht abschalten.
- Google Fonts sind nicht erreichbar bzw. nicht erwünscht. Schriften liegen
  lokal unter `assets/fonts/`.
- Unsplash: direkte Suche gesperrt (Auth). Einzelne Foto-IDs sind per
  `https://images.unsplash.com/photo-<ID>?w=...` ladbar. Pexels ist gesperrt.
  Openverse-API (`api.openverse.org`) und Wikimedia Commons sind erreichbar,
  liefern aber meist Screenshots oder lizenzpflichtige Bilder.
- Montage Gantt-Laptop: `quellen-ozcorapci.de/gantt-montage.py`.

## 7. Projektregeln (aus `CLAUDE.md`), die hier gelten

- Keine erfundenen Inhalte, fehlendes mit `[TODO: Kunde liefert]` markieren.
- Design-Token zuerst (`:root`), danach nur Variablen.
- Höchstens zwei Schriften (Ausnahme A: Unterschrift, siehe oben).
- Barrierefreiheit: Kontrast 4.5:1, sichtbarer Fokus, `prefers-reduced-motion`.
- Impressum und Datenschutz vor dem Go-Live.
- Handy zuerst, nichts scrollt bei 360px horizontal.
- Zugangsdaten nie ins Repo.

## 8. Chronik der Session (Kurzform)

1. Bestandsaufnahme der Hauptseite (alt: Sidebar, dunkel, violett).
2. Neuaufbau nach dem Consultant-Template (Schwarz/Gelb), Inhalte vom CV,
   freigestelltes Porträt, Unterschrift, Skill-Icons, Stockfotos.
3. Neuer CV: Werdegang aktualisiert, Abschnitt KI-Projekte.
4. Mobil: 15.755px auf rund 6.500px Höhe, Foto oben, Wisch-Karten.
5. Schriften selbst gehostet (Datenschutz). Entwürfe A/B/C gebaut, B und C
   verworfen, A neu gebaut mit dem Aufbau des Originals.
6. Feinschliff A: Hero-Übergang, Größen, Icons, Unterschrift-Schrift.
7. Bilder: Meeting (M5), Gantt-Laptop im Büro (Montage).
8. Doku: `OFFENE-PUNKTE-ozcorapci.de.md`, README, diese Übergabe.

## 9. Stand dieser Übergabe

Diese Datei und der Ordner `quellen-ozcorapci.de/` sind mit PR #164 auf `master`
gemergt (Branch `claude/lucid-ride-n7p88e`, nach dem Merge neu auf `master`
aufzusetzen). Es liegt nichts Ungesichertes mehr lokal.
