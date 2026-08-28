# cc-dienstleistungen-friedberg.de

Platzhalter-Ordner für ein anstehendes komplettes Redesign der Website [cc-dienstleistungen-friedberg.de](https://cc-dienstleistungen-friedberg.de).

## Über die Live-Seite

CC Dienstleistungen (Serkan Çimen) – Handwerksbetrieb für Maler-, Lackier- und Ausbauarbeiten in Friedberg (Hessen).

Leistungen: Trockenbau, Maler- und Lackierarbeiten, Raumgestaltung, Innenausbau, Tapezierarbeiten, Putztechniken, Fußbodenverlegung.

## Status

**Redesign-Entwurf steht** (`index.html`) — Pitch-Grundlage für das Kundengespräch, kein Produktivsystem.

- Einzelne, eigenständige `index.html`: Bilder sind als Data-URI eingebettet, nur die Schriften (Archivo, Source Sans 3) kommen von Google Fonts. Läuft ohne Build-Schritt auf jedem Webspace.
- Aufbau orientiert an gängigen Handwerker-Seiten: Topbar, Hero mit Foto, USP-Leiste, Leistungen als Foto-Karten, Betriebs-Sektion, Ablauf in 3 Schritten, Referenzgalerie, Kontakt mit Formular, mehrspaltiger Footer.

### Farbschema

Abgestimmt auf das **neue** Firmenlogo (Rot/Anthrazit auf Weiß, Slogan „Mein Name ist meine Werbung"):

| Token | Wert | Verwendung |
|---|---|---|
| `--brand` | `#D9261C` | Logo-Rot, Flächen und Buttons |
| `--brand-text` | `#C0201A` | dunklerer Rotton für kleinen Text auf Hell (Kontrast) |
| `--ink` | `#2B2B2B` | Logo-Anthrazit, Fließtext und Überschriften |

Neutraltöne bewusst in reinem Grau ohne Blau- oder Warmstich, passend zum Anthrazit des Logos. Die drei roten Quadrate des Logos kehren als Bildmarke im Header/Footer und als Marker der Abschnittslabels wieder.

### Logo

Das Firmenlogo steht vollständig in Header und Footer.

Quelldateien im Ordner: `logo CC Dienstleistungen 2026.pdf` (vom Kunden) und `logo CC Dienstleistungen 2026.jpg` (340 × 226 px).

Verwendet wird die Fassung aus dem PDF, nicht das JPEG: darin steckt dasselbe Motiv mit 873 × 562 px und einer eigenen Transparenzmaske. Das eingebettete Bild ist ein **CMYK-JPEG mit Adobe-Marker** — die Werte liegen dort invertiert vor und müssen vor der Umwandlung nach RGB zurückgedreht werden, sonst kippen die Farben (Haus wird rosa, Rot verschwindet). Die freigestellte Fassung liegt als `assets/logo.png`; in `index.html` ist sie auf 560 px Breite herunterskaliert als Data-URI eingebettet (~20 KB).

Das Logo ist für hellen Grund gezeichnet — Schrift, Haus und Slogan sind schwarz und verschwinden auf dunklem Untergrund. Deshalb liegt es in Header und Footer auf einer weißen Fläche mit etwas Innenabstand: im Hellmodus fällt die nicht auf, im Dunkelmodus und im dunklen Footer trägt sie das Logo.

Eine echte Vektorfassung gibt es nicht — auch im PDF ist das Logo ein Pixelbild. Für großformatige Anwendungen (Fahrzeugbeschriftung, Bauschild) müsste beim Grafiker eine SVG- oder EPS-Datei angefragt werden.

Die Reihenfolge der Leistungen folgt der Gewichtung des Logos: Trockenbau, Bodenverlegung, Innenausbau zuerst, danach die Oberflächen-Gewerke.
- Getestet in Chromium bei 320–1920 px (kein horizontales Scrollen) sowie im Dark Mode.

### Vor einem Livegang zu klären

- **Bildmaterial**: aktuell lizenzfreie Platzhalterfotos (Pexels-Lizenz). Vor dem Launch durch eigene Fotos von Baustellen und fertigen Arbeiten ersetzen — das ist der größte verbleibende Qualitätssprung.
- **Ungeprüfte Angaben** im Entwurf: „Handwerksbetrieb seit 2012", „12+ Jahre im Handwerk", „Umkreis 40 km", Öffnungszeiten „Mo–Fr 7:00–17:00", „Festpreis-Angebot", „eigenes Team, keine Subunternehmer". Mit dem Kunden gegenprüfen.
- **Kontaktformular** sendet noch nicht (kein Endpunkt angebunden).
- **Impressum und Datenschutzerklärung** fehlen noch (Footer-Links zeigen ins Leere).
