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

Das Logo selbst ist **noch nicht eingebunden** — dafür wird die Bilddatei benötigt. Bis dahin steht im Header eine Wortmarke, die den Aufbau des Logos nachbildet (»CC« in Anthrazit, »Dienstleistungen« in Rot).

Die Reihenfolge der Leistungen folgt der Gewichtung des Logos: Trockenbau, Bodenverlegung, Innenausbau zuerst, danach die Oberflächen-Gewerke.
- Getestet in Chromium bei 320–1920 px (kein horizontales Scrollen) sowie im Dark Mode.

### Vor einem Livegang zu klären

- **Bildmaterial**: aktuell lizenzfreie Platzhalterfotos (Pexels-Lizenz). Vor dem Launch durch eigene Fotos von Baustellen und fertigen Arbeiten ersetzen — das ist der größte verbleibende Qualitätssprung.
- **Ungeprüfte Angaben** im Entwurf: „Handwerksbetrieb seit 2012", „12+ Jahre im Handwerk", „Umkreis 40 km", Öffnungszeiten „Mo–Fr 7:00–17:00", „Festpreis-Angebot", „eigenes Team, keine Subunternehmer". Mit dem Kunden gegenprüfen.
- **Kontaktformular** sendet noch nicht (kein Endpunkt angebunden).
- **Impressum und Datenschutzerklärung** fehlen noch (Footer-Links zeigen ins Leere).
