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

- **Bildmaterial**: aktuell Platzhalterfotos von Pexels. Vor dem Launch durch eigene Fotos von Baustellen und fertigen Arbeiten ersetzen — das ist der größte verbleibende Qualitätssprung. Zur Rechtslage siehe unten.
- **Ungeprüfte Angaben** im Entwurf: „Handwerksbetrieb seit 2012", „12+ Jahre im Handwerk", „Umkreis 40 km", Öffnungszeiten „Mo–Fr 7:00–17:00", „Festpreis-Angebot", „eigenes Team, keine Subunternehmer". Mit dem Kunden gegenprüfen.
- **Kontaktformular** sendet noch nicht (kein Endpunkt angebunden).
- **Impressum und Datenschutzerklärung** fehlen noch (Footer-Links zeigen ins Leere).

## Bildrechte

Alle acht Fotos stammen von [Pexels](https://www.pexels.com/license/). Die Lizenz erlaubt kommerzielle Nutzung und Bearbeitung und verlangt keine Namensnennung. Verwendete Bilder:

| Verwendung | Pexels-ID | Person erkennbar |
|---|---|---|
| Hero | [36153946](https://www.pexels.com/photo/construction-workers-painting-interior-walls-36153946/) | **ja** — zwei Handwerker, Gesichter sichtbar |
| Trockenbau | [11427524](https://www.pexels.com/photo/man-doing-construction-work-inside-house-11427524/) | **ja** — Handwerker im Profil |
| Bodenverlegung | [4263067](https://www.pexels.com/photo/crop-man-installing-laminate-flooring-4263067/) | Grenzfall — kein Gesicht, aber markante Tattoos |
| Betrieb (Maler an der Decke) | [5493653](https://www.pexels.com/photo/back-view-of-a-person-painting-the-wall-using-a-painting-brush-roller-5493653/) | nein — von hinten, Helm |
| Maler- und Lackierarbeiten | [7218011](https://www.pexels.com/photo/people-painting-the-wall-7218011/) | nein — nur Hand |
| Putztechniken | [994164](https://www.pexels.com/photo/person-holding-paint-roller-while-painting-the-wall-994164/) | nein — nur Hand |
| Innenausbau | [6180674](https://www.pexels.com/photo/modern-interior-of-comfortable-living-room-6180674/) | nein — leerer Raum |
| Tapezierarbeiten | [3316922](https://www.pexels.com/photo/white-and-brown-modern-living-room-interior-design-3316922/) | nein — leerer Raum |

### Offener Punkt für den Livegang

Die Pexels-Lizenz deckt nur das Urheberrecht des Fotografen ab, sie liefert **kein Model Release**. Bei erkennbaren Personen greift in Deutschland zusätzlich das Recht am eigenen Bild (§ 22 KUG), und bei werblicher Nutzung — nichts anderes ist eine Firmenwebsite — wird das strenger bewertet als bei redaktioneller. Verschärfend kommt hinzu, dass die Fotos im Seitenkontext nahelegen, es handele sich um Mitarbeiter des Betriebs.

Betroffen sind die drei oben markierten Bilder. Erkennbarkeit endet dabei nicht beim Gesicht: auch Tattoos, Frisur oder andere auffällige Merkmale genügen, wenn Bekannte die Person wiedererkennen würden.

Für den Pitch ist der Stand unkritisch. Vor dem öffentlichen Livegang sollten die Fotos ohnehin durch eigene Aufnahmen ersetzt werden — das erledigt diesen Punkt gleich mit.

Das Firmenlogo ist Eigentum des Kunden und von dieser Betrachtung nicht berührt.
