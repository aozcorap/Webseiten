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
- **Logo-Größe**: Das Logo trug den Kopfbereich zunächst mit voller Wortmarken-Breite (232 px) — dadurch wurde allein der Header 171 px hoch. Jetzt 120 px auf Desktop, gestaffelt bis 82 px auf sehr schmalen Schirmen; der Header ist entsprechend kompakter.
- **Anker-Sprünge unter dem stickyen Header.** `header.site` ist `position: sticky`. Ohne Gegenmaßnahme landen `#top` und alle Sektions-Anker (Logo-Klick, Menüpunkte, Telefon-CTA) unter dem Header versteckt — die obersten Zeilen der Ziel-Sektion sind dann nicht sichtbar. Behoben über `scroll-padding-top` auf `html`, gestaffelt nach der gemessenen Kopfbereich-Höhe (Topbar + Header-Zeile) je Breite. Nachgemessen mit `scrollIntoView()` bei sechs Breiten und vier Zielen (`#top`, `#leistungen`, `#betrieb`, `#kontakt`): kein Ziel mehr verdeckt.
  Zum Nachmessen: `reducedMotion: 'reduce'` beim Playwright-Kontext setzen, sonst liefert `scrollIntoView()` mitten in der `smooth`-Animation falsche Werte.
- **Header-CTA**: Zeigte ein Telefon-Icon, führte aber zum Kontaktformular statt zum Anruf, und war mit „Anfragen" beschriftet. Jetzt konsistent: Beschriftung „Jetzt anrufen" (mobil „Anrufen"), Ziel `tel:+4960311609098`.
- **Hero-CTAs**: Die primäre Schaltfläche zeigte die Telefonnummer als Label, obwohl der Header-CTA daneben „Jetzt anrufen" sagt — zwei Beschriftungsmuster für dieselbe Aktion. Jetzt einheitlich „Jetzt anrufen". Die zweite Schaltfläche hieß „Kostenloses Angebot", ohne zu sagen, wie das Angebot ankommt; jetzt „Angebot per E-Mail".
- **Kontaktformular ohne E-Mail-Feld.** Das Formular versprach ein Angebot, fragte aber nur Name und Telefon ab — ohne E-Mail-Adresse hätte niemand ein Angebot zugestellt bekommen können. E-Mail-Feld ergänzt (Pflichtfeld), Telefon dafür auf optional gesetzt. Hinweistext unter dem Formular sagt jetzt explizit, dass das Angebot an die angegebene Adresse geht.
- **Vier generische Kontaktkarten im Kontaktbereich** (Festnetz, Mobil & WhatsApp, E-Mail, Adresse) wiederholten nur, was bereits in Topbar und Footer steht, ohne dem Kunden bei der Entscheidung zu helfen. Ersetzt durch drei Nutzenaussagen (Rückmeldung am selben Werktag, Festpreis vor Auftragsstart, kostenlose Besichtigung vor Ort) plus eine schlanke Zeile „Lieber gleich sprechen?" mit Telefon- und WhatsApp-Link für alle, die das Formular überspringen wollen.
- **Galerie**: Das Seitenverhältnis liegt auf der Kachel, nicht auf dem Bild. Die breite Kachel bekommt `8/3` statt `4/3`, weil sie doppelt so breit ist — sonst wächst sie auch doppelt in die Höhe und sprengt die Zeile.

### SEO-Basics

Bewusst nur die Grundlagen, keine Kampagne:

- **Meta-Tags** je Seite: eigener `<title>`, `<meta name="description">`, Open Graph (`og:title`, `og:description`, `og:image`, `og:type`, `og:locale`, `og:site_name`) und Twitter-Card-Tags. `og:image` nutzt einen zugeschnittenen Ausschnitt des Hero-Fotos (1200×630, eingebettet) — wichtig schon jetzt, weil ein per WhatsApp verschickter Pitch-Link damit eine Vorschau zeigt.
- **`<meta name="robots" content="noindex, nofollow">`** auf allen drei Seiten. Das ist bewusst *keine* SEO-Maßnahme für die Zielseite, sondern eine Sicherung für die Entwurfsphase: Diese Kopie läuft unter `aozcorap.github.io/...`, nicht unter der echten Domain. Ohne `noindex` könnte Google sie indexieren und später mit der echten Seite unter `cc-dienstleistungen-friedberg.de` als Duplicate Content konkurrieren. **Vor dem echten Livegang entfernen** (in allen drei `<head>`-Bereichen).
- **`rel="canonical"`** zeigt bereits auf `https://cc-dienstleistungen-friedberg.de/…` — die vermutete Zieladresse. Beim tatsächlichen Go-Live prüfen, ob das noch stimmt.
- **Strukturierte Daten** (JSON-LD, `HomeAndConstructionBusiness`) auf der Startseite: Name, Adresse, Telefon, E-Mail, Einsatzgebiet, Öffnungszeiten, Leistungen als `Offer`-Liste. Es werden ausschließlich Angaben verwendet, die ohnehin sichtbar auf der Seite stehen — nichts Neues behauptet. Das schließt die weiter unten gelisteten ungeprüften Angaben ein (`foundingDate: "2012"`, Öffnungszeiten): vor dem Livegang zusammen mit dem übrigen Text gegenprüfen.
- **Favicon** eingebettet (die drei roten Logo-Quadrate als eigenständige 64×64-Marke, kein Downscale des vollen Logos).
- Überschriften-Hierarchie sauber (ein `h1` pro Seite), `lang="de"`, sprechende Alt-Texte an allen Fotos — war größtenteils schon vorher in Ordnung.
- **Kein `robots.txt` / `sitemap.xml`** im Ordner: Diese Dateien wirken nur am Domain-Root, und der Root dieses Repos wird von allen Projekten gemeinsam genutzt (`aozcorap.github.io/Webseiten/…`). Eine Datei hier hätte keine Wirkung und ein Root-`robots.txt` würde andere Kundenprojekte im selben Repo mit betreffen. Nachzuholen, sobald die Seite einen eigenen Domain-Root hat.

### Schriften: selbst gehostet statt Google Fonts

Die Seite lud Archivo und Source Sans 3 zuvor direkt von `fonts.googleapis.com`/`fonts.gstatic.com`. Das ist in Deutschland ein bekanntes Abmahnrisiko: Der Aufruf überträgt die Besucher-IP ohne Einwilligung an Google-Server (u. a. LG München I, Az. 3 O 17493/20 — IP-Übertragung als DSGVO-Verstoß gewertet).

Alle 8 benötigten Schnitte (Archivo 500/600/700/800, Source Sans 3 400/500/600/700, jeweils der lateinische Subset `U+0000-00FF`, deckt deutsche Umlaute ab) sind jetzt als WOFF2 lokal eingebettet (`@font-face` mit `data:`-URI, siehe `fonts/manifest.json` als Referenz der Originaldateien). **Ergebnis: Die Seite baut keine einzige externe Verbindung mehr auf** — mit Playwright nachgemessen (`page.on('request', …)` bei `networkidle`): 0 externe Anfragen auf allen drei Seiten. Das ist auch der Grund, warum kein Cookie-Banner nötig ist (siehe Datenschutzerklärung).

Kostet rund 330 KB zusätzlich pro Seite (Base64-Overhead eingerechnet) — vertretbar angesichts der ohnehin eingebetteten Fotos.

### Impressum & Datenschutz als echte Unterseiten

`impressum.html` und `datenschutz.html` liegen jetzt als eigenständige Seiten neben `index.html`, im selben Design (eigene, schlankere Kopie des Kopf-/Fußbereichs — bewusst dupliziert statt eines Includes, passend zur Repo-Konvention „reines HTML, kein Build-Schritt"). Footer-Links auf allen drei Seiten zeigen jetzt wirklich dorthin, die aktive Seite ist im Footer per `aria-current="page"` fett hervorgehoben.

**Impressum** enthält die real bekannten Angaben (Name, Anschrift, Telefon, E-Mail aus der bisherigen Live-Seite). Sichtbar als `[TODO: Kunde liefert]` markiert, weil nicht bekannt bzw. bewusst nicht übernommen:
- **USt-IdNr.** — auf der alten Seite stand eine *Steuernummer*. Die ist keine Pflichtangabe im Impressum und wird aus Vorsicht **nicht** übernommen (öffentlich einsehbare Steuernummern sind unüblich und teils missbrauchsanfällig); stattdessen nach der USt-IdNr. gefragt.
- **Handwerkskammer + Eintragungsnummer in der Handwerksrolle** — Malerhandwerk ist zulassungspflichtig (Anlage A HwO), die Kammer-Angabe ist damit Pflicht, fehlt aber noch.

**Datenschutzerklärung** ist auf den tatsächlichen Stand dieses Entwurfs zugeschnitten (keine Cookies, keine Analyse-Dienste, selbst gehostete Schriften, GitHub-Pages-Hosting für die Entwurfsphase, Google-Maps-Link statt -Einbettung). Zwei Stellen sind als `[TODO: Vor dem Livegang aktualisieren]` markiert: der Hosting-Abschnitt (muss auf den echten Webspace umgeschrieben werden) und der Formular-Abschnitt (das Kontaktformular hat noch keinen Versand-Endpunkt — es werden aktuell tatsächlich keine Daten übertragen, der Text beschreibt den Zielzustand nach Anbindung).

### Agenten-Review (Code, SEO, Recht/Datenschutz)

Vor der Auslieferung von drei unabhängigen Agenten parallel geprüft. Reale Befunde, alle behoben:

- **Formularfelder ohne `name`-Attribut** — nur `id` gesetzt, kein `name`. Beim Anbinden an einen echten Endpunkt wären die Werte nicht oder ohne erkennbaren Feldnamen angekommen. Ergänzt.
- **„WhatsApp"-Link führte zum Telefonwähler statt zu WhatsApp** — Text sagte „WhatsApp 0152 …", `href` war aber `tel:`. Auf `https://wa.me/4915233680542` korrigiert.
- **`§ 5 TMG`-Verweis im Impressum veraltet** — das TMG wurde zum 14. Mai 2024 vom Digitale-Dienste-Gesetz (DDG) abgelöst. Auf `§ 5 DDG` bzw. `§§ 7–10 DDG` aktualisiert, mit kurzer Erklärung im Text.
- **`twitter:image` und `og:url` fehlten** in den Meta-Tags aller drei Seiten — ergänzt.
- **Title/Description der Startseite zu lang** (79 / 175 Zeichen) für eine saubere Darstellung in Suchergebnissen — auf 54 / 132 Zeichen gekürzt.

Sonst nichts Kritisches: keine doppelten IDs, alle internen Anker und Datei-Links funktionieren, alle Formularfelder haben zugehörige `<label>`, alle Bilder sinnvolle Alt-Texte, JSON-LD ist syntaktisch valide und schema.org-konform, EU-Streitschlichtungs-Hinweis korrekt ohne die seit Juli 2025 eingestellte OS-Plattform formuliert, Datenschutztext deckungsgleich mit der technischen Realität (0 externe Anfragen, Kontaktformular ehrlich als noch nicht angebunden beschrieben).

**Wichtig:** Dieser Review ersetzt keine anwaltliche Prüfung. Vor dem echten Go-Live sollten Impressum und Datenschutzerklärung von einem Anwalt oder der zuständigen Handwerkskammer gegengelesen werden — insbesondere wegen der noch offenen Handwerkskammer-Angabe.

### Vor einem Livegang zu klären

- **Ungeprüfte Angaben** im Entwurf: „Umkreis 40 km", Öffnungszeiten „Mo–Fr 7:00–17:00", „Festpreis-Angebot", „eigenes Team, keine Subunternehmer" — dieselben Werte stecken jetzt auch im JSON-LD, siehe SEO-Abschnitt. (Gründungsjahr 2011 und „15+ Jahre im Handwerk" sind vom Kunden bereits bestätigt.)
- **Kontaktformular** sendet noch nicht (kein Endpunkt angebunden). Die Pflicht-Checkbox zur Einwilligung in die Datenverarbeitung ist bereits im Markup vorhanden.
- **Impressum**: USt-IdNr. und Handwerkskammer-Eintrag fehlen noch (siehe oben).
- **`noindex`-Tag** vor dem echten Go-Live aus allen drei Seiten entfernen (siehe SEO-Abschnitt).

### Kundenkorrekturen (diese Session)

- **Gründungsjahr**: 2011 statt 2012 (Hero-Badge, About-Sektion, JSON-LD). „12+ Jahre im Handwerk" entsprechend auf „15+" angehoben.
- **Nur noch Handynummer**: Die Festnetznummer (06031 160 90 98) ist von allen drei Seiten entfernt, inklusive Impressum. Sämtliche Anruf-Links, Telefonanzeigen und der JSON-LD-Eintrag zeigen jetzt ausschließlich auf die Handynummer 0152 336 805 42.
- **Neue Kontakt-E-Mail**: `serkan@gmail.info` ersetzt überall `info@cc-dienstleistungen-friedberg.de` (Formular-Hinweis, Footer, Topbar, Impressum, Datenschutzerklärung, JSON-LD).
- **Einwilligungs-Checkbox im Kontaktformular**: Pflichtfeld „Ich habe die Datenschutzerklärung gelesen und stimme der Verarbeitung meiner Angaben zur Bearbeitung dieser Anfrage zu", verlinkt auf `datenschutz.html`. Die Datenschutzerklärung nennt dafür jetzt zusätzlich Art. 6 Abs. 1 lit. a DSGVO als Rechtsgrundlage neben der Vertragserfüllung nach lit. b.

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

Der Kunde hat entschieden, die Pexels-Fotos dauerhaft zu übernehmen — keine eigenen Aufnahmen geplant.

Das Firmenlogo ist Eigentum des Kunden und von dieser Betrachtung nicht berührt.
