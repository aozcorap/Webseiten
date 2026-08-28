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

Neutraltöne bewusst in reinem Grau ohne Blau- oder Warmstich, passend zum Anthrazit des Logos. Die roten Quadrate des Logos kehren als Marker der Abschnittslabels wieder.

**Kein Dark Mode.** Die Seite ist bewusst einthemig hell. Das Logo ist Schwarz/Rot auf Weiß gezeichnet: auf dunklem Grund verschwinden Schriftzug, Haus und Slogan, und eine weiße Trägerfläche darunter wirkt als Fremdkörper. Deshalb sind auch Footer und Kopfbereich hell gehalten; dunkel bleiben nur der schmale Kontaktstreifen ganz oben, das Hero-Foto und der Kontaktblock — dort steht kein Logo.

### Logo

Das Firmenlogo steht vollständig in Header und Footer.

Quelldateien im Ordner: `logo CC Dienstleistungen 2026.pdf` (vom Kunden) und `logo CC Dienstleistungen 2026.jpg` (340 × 226 px).

Verwendet wird die Fassung aus dem PDF, nicht das JPEG: darin steckt dasselbe Motiv mit 873 × 562 px und einer eigenen Transparenzmaske. Das eingebettete Bild ist ein **CMYK-JPEG mit Adobe-Marker** — die Werte liegen dort invertiert vor und müssen vor der Umwandlung nach RGB zurückgedreht werden, sonst kippen die Farben (Haus wird rosa, Rot verschwindet). Die freigestellte Fassung liegt als `assets/logo.png`; in `index.html` ist sie auf 560 px Breite herunterskaliert als Data-URI eingebettet (~20 KB).

Das Logo ist für hellen Grund gezeichnet — Schrift, Haus und Slogan sind schwarz und verschwinden auf dunklem Untergrund. Deshalb liegt es in Header und Footer auf einer weißen Fläche mit etwas Innenabstand: im Hellmodus fällt die nicht auf, im Dunkelmodus und im dunklen Footer trägt sie das Logo.

Eine echte Vektorfassung gibt es nicht — auch im PDF ist das Logo ein Pixelbild. Für großformatige Anwendungen (Fahrzeugbeschriftung, Bauschild) müsste beim Grafiker eine SVG- oder EPS-Datei angefragt werden.

Die Reihenfolge der Leistungen folgt der Gewichtung des Logos: Trockenbau, Bodenverlegung, Innenausbau zuerst, danach die Oberflächen-Gewerke.
- Getestet in Chromium bei 320–1920 px (kein horizontales Scrollen).
- **Vollständiges HTML-Dokument.** `index.html` ist eine eigenständige Seite mit `<!doctype>`, `<html lang="de">` und Kopfbereich. Das **`viewport`-Meta-Tag ist zwingend**: ohne es rendern Handys die Seite in ~980 px Breite und skalieren sie herunter — die Schrift wird unlesbar klein und keine einzige Mobil-Regel greift. Nachgemessen: 980 px Layout-Breite ohne das Tag, 390 px mit.
  Beim Testen reicht es **nicht**, im Browser eine schmale Fensterbreite zu setzen — das verhält sich, als wäre das Tag vorhanden. Es braucht echte Geräte-Emulation (in Playwright `devices['iPhone 13']`), sonst bleibt der Fehler unsichtbar.
- **Schriftgrößen**: Alle `rem`-Angaben hängen an der Wurzelgröße, nicht an `body`. Die Wurzel steht deshalb auf 17 px, unterhalb 620 px auf 17,5 px — so skaliert der gesamte Textsatz gleichmäßig, statt klassenweise nachjustiert zu werden. Fließtext 17 px (Desktop) bzw. 17,5 px (Handy), kleinster Text 13,6 bzw. 14 px.
- **Galerie**: Das Seitenverhältnis liegt auf der Kachel, nicht auf dem Bild. Die breite Kachel bekommt `8/3` statt `4/3`, weil sie doppelt so breit ist — sonst wächst sie auch doppelt in die Höhe und sprengt die Zeile.

### Vor einem Livegang zu klären

- **Bildmaterial**: aktuell Platzhalterfotos von Pexels. Vor dem Launch durch eigene Fotos von Baustellen und fertigen Arbeiten ersetzen — das ist der größte verbleibende Qualitätssprung. Zur Rechtslage siehe unten.
- **Ungeprüfte Angaben** im Entwurf: „Handwerksbetrieb seit 2012", „12+ Jahre im Handwerk", „Umkreis 40 km", Öffnungszeiten „Mo–Fr 7:00–17:00", „Festpreis-Angebot", „eigenes Team, keine Subunternehmer". Mit dem Kunden gegenprüfen.
- **Kontaktformular** sendet noch nicht (kein Endpunkt angebunden).
- **Impressum und Datenschutzerklärung** fehlen noch (Footer-Links zeigen ins Leere).

## Bildrechte

Alle acht Fotos stammen von [Pexels](https://www.pexels.com/license/). Die Lizenz erlaubt kommerzielle Nutzung und Bearbeitung und verlangt keine Namensnennung.

Sie deckt allerdings **kein Model Release** ab. Bei erkennbaren Personen greift in Deutschland zusätzlich das Recht am eigenen Bild (§ 22 KUG), und bei werblicher Nutzung — nichts anderes ist eine Firmenwebsite — wird das strenger bewertet als bei redaktioneller. Verschärfend kommt hinzu, dass die Fotos im Seitenkontext nahelegen, es handele sich um Mitarbeiter des Betriebs.

Deshalb wurden die drei Motive mit erkennbaren Personen ausgetauscht. Der aktuelle Bestand kommt ohne erkennbare Personen aus:

| Verwendung | Pexels-ID | Motiv |
|---|---|---|
| Hero | [7865621](https://www.pexels.com/photo/empty-interior-with-white-walls-and-wooden-floor-7865621/) | fertiger heller Raum, weiße Wände, Holzboden |
| Trockenbau | [6474313](https://www.pexels.com/photo/wooden-materials-in-a-room-6474313/) | Gipskartondecke mit verspachtelten Fugen, Gerüst |
| Bodenverlegung | [16641359](https://www.pexels.com/photo/an-empty-room-in-a-modern-house-16641359/) | Raum mit verlegtem Holzboden |
| Innenausbau | [6180674](https://www.pexels.com/photo/modern-interior-of-comfortable-living-room-6180674/) | eingerichteter Wohnraum |
| Maler- und Lackierarbeiten | [7218011](https://www.pexels.com/photo/people-painting-the-wall-7218011/) | Hand mit Farbrolle |
| Putztechniken | [994164](https://www.pexels.com/photo/person-holding-paint-roller-while-painting-the-wall-994164/) | Hand mit Farbrolle an der Wand |
| Tapezierarbeiten | [3316922](https://www.pexels.com/photo/white-and-brown-modern-living-room-interior-design-3316922/) | eingerichteter Wohnraum |
| Betriebs-Sektion | [5493653](https://www.pexels.com/photo/back-view-of-a-person-painting-the-wall-using-a-painting-brush-roller-5493653/) | Maler von hinten, Helm — kein Gesicht, keine erkennbaren Merkmale |

Zwei Motive zeigen nur eine Hand, eines eine Person von hinten mit Helm. Erkennbarkeit im Sinne des KUG setzt voraus, dass Bekannte die Person wiedererkennen würden — Gesicht, aber auch Tattoos, Frisur oder andere auffällige Merkmale. Keines davon ist hier gegeben.

Unabhängig davon bleiben die Fotos Platzhalter und sollten vor dem Livegang durch eigene Aufnahmen ersetzt werden.

Das Firmenlogo ist Eigentum des Kunden und von dieser Betrachtung nicht berührt.
