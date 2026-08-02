# Offene Punkte – Boxring Wetterau SEO/AEO (Stand 2026-07-31, Ende Session)

## Offen
- [ ] Echte PageSpeed-Insights-Analyse (Mobile + Desktop) noch nicht möglich:
      PSI-API lieferte 429 (Tageskontingent des Projekts auf 0 gesetzt),
      Versuch über Headless-Browser gegen pagespeed.web.dev scheiterte an
      einem Proxy-/TLS-Problem in der Claude-Umgebung (ERR_CONNECTION_RESET),
      nicht an der Website selbst. Kein akuter Handlungsbedarf – strukturell
      spricht nichts gegen ein gutes Ergebnis (self-gehostete Fonts, kleine
      HTML-Größe ~37 KB, TTFB ~0,46s, keine render-blockenden Ressourcen).
      Falls gewünscht: Nutzer kann https://pagespeed.web.dev selbst einmal
      aufrufen und Ergebnis/Screenshot durchgeben zur Einordnung.
- [ ] SVG-Logo (assets/logo-white.svg, ~90 KB) ist für ein einfarbiges Logo
      ungewöhnlich groß, vermutlich unoptimierte Pfade/Metadaten aus dem
      Export. Kein Ranking-Problem, nur kleine Performance-Bremse. Sollte
      vor einer Optimierung (z. B. SVGO) visuell geprüft werden, damit das
      Logo nicht kaputtgeht – nicht ohne visuelle Kontrolle automatisiert
      anfassen.
- [ ] Laufend: weitere Erfolge einpflegen, sobald neue Instagram-Posts/Fotos
      vom Nutzer kommen. Ablauf (siehe auch "Hintergrund-Infos"):
      1. Nutzer schickt Screenshot (Bild + Text) im Chat + Datum
      2. Claude schlägt Dateinamen vor (Schema erfolge-JJJJ-MM-TT-kurzname.jpg)
         und einen leicht optimierten Kurztext
      3. Nutzer lädt Original-Foto (ohne Instagram-UI) selbst per Plesk-File-
         Manager in assets/ hoch (Chat-Bilder sind für Claude nicht als Datei
         zugänglich, siehe Hintergrund-Infos)
      4. Neuer Erfolg wird in erfolge.html ergänzt (chronologisch, neuester
         zuerst) und ggf. in den Startseiten-Teaser aufgenommen, falls er
         unter die neuesten 4 fällt (ältester Teaser-Eintrag rutscht dann
         auf die Unterseite)
      5. Erst wenn der Nutzer sagt "jetzt deployen": neue ZIP bauen +
         bereitstellen
      Feste Redaktionsregel: Trainer Ahmet Özcorapci (Head Coach) wird im
      Erfolge-Text NUR bei großen Turnieren namentlich erwähnt (Deutsche
      Meisterschaft, Hessenmeisterschaft, Stadtmeisterschaft o. ä.) – auch
      wenn dort nicht der 1. Platz erreicht wurde. Bei normalen Turnieren/
      Wettkampfsparrings bleibt der Fokus auf dem Athleten, kein Trainer-
      Name. Rückwirkend bereits ergänzt bei Eintrag Mezhidov/Hessenmeister-
      schaft (25.9.2021) und beim neuen Eintrag zur ersten DM-Teilnahme
      (21.11.2021).
      Weitere Redaktionsregel: Bei mehrtägigen Turnierserien mit Tages-Posts
      pro Kampfrunde (z. B. Deutsche Meisterschaft mit täglichem "nächste
      Runde erreicht"-Post) NICHT jeden Tagespost als eigenen Erfolg
      übernehmen, sondern zu EINEM zusammenfassenden Eintrag mit Endergebnis
      verdichten (Turnierverlauf ggf. kurz erwähnen, z. B. "im Achtelfinale
      gegen X, im Halbfinale gegen Y"). Aktueller Stand: Nutzer pausiert das
      Nachliefern weiterer Erfolge vorerst, liefert bei Gelegenheit neue
      nach – aktuell 15 Erfolge insgesamt eingepflegt.
- [ ] Antwort von Strothmann IT abwarten: Domain-Tausch anfragen –
      boxring-wetterau.de soll Hauptdomain werden, boxring-woelfersheim.de
      wird 301-Weiterleitung dorthin (Nachricht an Hoster wurde bereits formuliert
      und vom Nutzer verschickt)
- [ ] Rezensionen aktiv einsammeln (letzter offener GBP-Punkt, größter noch
      ungenutzter Ranking-Hebel für die Umkreissuche/Local Pack): Text für
      Rezensions-Anfrage wurde formuliert (WhatsApp-Vorlage), Zielgruppe
      bewusst auf Eltern + erwachsene Mitglieder (Masterboxen etc.)
      fokussiert statt breit auf Jugendliche, da diese kaum Google nutzen.
      Empfehlung: 5–10 Bewertungen über 2–3 Wochen verteilt einholen statt
      alle auf einmal (wirkt organischer)
- [ ] NAP-Eintrag (Name/Adresse/Telefon) im LSB Hessen Vereinsverzeichnis
      ergänzen (Stadt Friedberg Vereinsverzeichnis bereits erledigt, siehe
      unten; HABV-Verbandsseite bereits selbst durch Nutzer eingetragen;
      DOSB-Punkt gestrichen – DOSB ist reiner Dachverband der Landessport-
      bünde/Spitzenverbände ohne eigene Vereins-Selbstregistrierung, der
      Verein ist über HABV + LSB Hessen ohnehin indirekt Teil der DOSB-
      Struktur; Das Örtliche/Gelbe Seiten bewusst rausgenommen – primär
      Firmenverzeichnisse, für einen Verein wenig SEO-Nutzen)
- [ ] Google Search Console: Domainverifizierung über GoDaddy abschließen,
      danach sitemap.xml (https://www.boxring-wetterau.de/sitemap.xml) einreichen

## Bereits erledigt
- [x] Senior-Webdesigner/SEO-Review der Live-Seite durchgeführt, 6 Issues
      angelegt (#50–#55) und direkt im Code gefixt:
      - #50 (High): Non-www-Domain (boxring-wetterau.de ohne www) lieferte
        200 statt 301 auf www → .htaccess mit RewriteRule ergänzt
      - #51 (Medium): 24 Erfolge-Fotos unoptimiert (250 KB–2 MB je Bild,
        teils bis 3753px Kantenlänge) → von der Live-Seite geladen, auf
        max. 900px Kantenlänge verkleinert und neu komprimiert (JPEG
        q78 + WebP q78), Gesamtgröße 8,9 MB → 2,5 MB (JPEG-Fallback) bzw.
        1,6 MB (WebP). erfolge.html + Startseiten-Teaser auf
        <picture>-Element mit WebP-Source umgestellt (JPEG-Fallback für
        ältere Browser bleibt erhalten)
      - #52 (Low): Sicherheits-Header (HSTS, X-Content-Type-Options,
        X-Frame-Options) fehlten → in .htaccess ergänzt
      - #53 (Low): Google-Maps-iframe ohne title-Attribut → ergänzt
      - #54 (Low): Instagram-Link ohne target/rel → target="_blank"
        rel="noopener noreferrer" ergänzt
      - #55 (Low): keine Mobile-Icons → apple-touch-icon.png (180×180,
        aus favicon.png generiert), manifest.json und
        <meta name="theme-color"> ergänzt
      Deployment abgeschlossen, live verifiziert (02.08.2026): 301-Redirect,
      Security-Header, WebP/verkleinerte JPEGs, apple-touch-icon/manifest,
      iframe-title und Instagram-Link-Fix per curl gegen die Live-Seite
      bestätigt. Alle 6 Issues geschlossen.
- [x] Live-Audit durchgeführt: index.html, erfolge.html, sitemap.xml, robots.txt
      und JSON-LD (SportsClub + FAQPage) sind byte-identisch zum Code-Stand
      im Repo. Dabei einen Deploy-Bug gefunden und vom Nutzer direkt per
      Plesk behoben: Bild assets/erfolge-2025-01-17-auguste-erster-sieg.jpg
      war auf dem Server als .JPG (Großbuchstaben) abgelegt, Code erwartet
      .jpg – auf dem case-sensitiven Linux-Server führte das zu einem
      kaputten Bild bei "Auguste gewinnt seinen ersten Kampf" (17.1.2025)
      auf erfolge.html. Live bestätigt (200 statt 404).
- [x] 4 neue Erfolge zu Max Auguste (Nachwuchsboxer, fortlaufende Serie
      "X. Kampf, X. Sieg") in erfolge.html + Startseiten-Teaser eingebaut,
      chronologisch neuester zuerst:
      - 17.1.2025 – Walldorf: 1. Kampf, 1. Sieg (Debüt). Kein Trainer-
        Verweis. Dateiname: erfolge-2025-01-17-auguste-erster-sieg.jpg
      - 21.2.2026 – Anfängerturnier, Bruchköbel: 2. Kampf, 2. Sieg. Kein
        Trainer-Verweis. Dateiname:
        erfolge-2026-02-21-auguste-zweiter-sieg.jpg
      - 21.3.2026 – Marburg: 3. Kampf, 3. Sieg. Kein Trainer-Verweis.
        Dateiname: erfolge-2026-03-21-auguste-dritter-sieg.jpg
      - 10.5.2026 – Walldorf: 4. Kampf, 4. Sieg. Trainer Ahmet Özcorapci
        posiert mit im Foto, daher im Alt-Text genannt, aber NICHT im
        Fließtext (kein großes Turnier) – Alt-Text beschreibt nur, was
        sichtbar ist, unabhängig von der Trainer-Erwähnungsregel für den
        Fließtext. Dateiname: erfolge-2026-05-10-auguste-vierter-sieg.jpg
      Neue Teaser-4 auf Startseite: Duelle der Champions, Auguste-4.-Sieg,
      Gencer-TKO, Auguste-3.-Sieg (bisherige Teaser-Einträge Mezhidov-
      Hessenmeister und Deichert+Mezhidov-Viertel/Halbfinale rutschen auf
      die Unterseite, bleiben dort aber natürlich weiter sichtbar). Bilder
      wurden vom Nutzer bereits per Plesk hochgeladen. ZIP wurde
      bereitgestellt, Nutzer hat per FTP deployed. Deployment abgeschlossen.
- [x] 3 neue Erfolge rund um Mansur Mezhidov / Hessenmeisterschaft der Elite
      in erfolge.html + Startseiten-Teaser eingebaut, chronologisch neuester
      zuerst:
      - 31.8.2025 – Vorbereitungskampf, Bensheim: Mezhidov siegt zur
        Einstimmung auf die Hessenmeisterschaft. Kein Trainer-Verweis (kein
        großes Turnier selbst). Dateiname:
        erfolge-2025-08-31-mezhidov-vorbereitung-hessenmeisterschaft.jpg
      - 21.9.2025 – Hessenmeisterschaft der Elite, Darmstadt: Mezhidov
        (bis 65 kg) siegt im Viertelfinale, Dominik Deichert (bis 80 kg)
        siegt im Halbfinale (direkt ohne Viertelfinale angetreten).
        Turnierserien-Redaktionsregel angewendet: EIN zusammenfassender
        Beitrag statt Einzelposts pro Runde. Trainer erwähnt (Hessen-
        meisterschaft = großes Turnier). Dateiname:
        erfolge-2025-09-21-deichert-mezhidov-hessenmeisterschaft.jpg
      - 28.9.2025 – Finale, Kassel: Mezhidov wird Hessenmeister im
        Weltergewicht (bis 65 kg), einstimmig nach Punkten. Dominik Deichert
        musste krankheitsbedingt kampflos passen – laut Nutzer gibt es in
        diesem Fall KEINEN Vize-Hessenmeister-Titel (kein Finalkampf =
        keine Platzierung), im Text entsprechend neutral formuliert statt
        einen Titel zu erfinden. Trainer erwähnt. Dateiname:
        erfolge-2025-09-28-mezhidov-hessenmeister.jpg
      Bildauswahl für den Hessenmeister-Beitrag: Nutzer hatte 3 Fotos zur
      Auswahl (Trainer+Urkunde / Porträt mit Medaillen / Ring-Moment mit
      Schiedsrichterin), gemeinsam Bild "Mansur + Trainer mit Urkunde und
      Fellmützen" gewählt (emotionalste + gleichzeitig fungiert es als
      Beleg für den Titel).
      Neue Teaser-4 auf Startseite: Duelle der Champions, Gencer-TKO,
      Mezhidov-Hessenmeister, Deichert+Mezhidov-Viertel/Halbfinale
      (bisherige Teaser-Einträge Guirguis-KO und Özcorapci-Deutscher-
      Meister-Masterboxen rutschen auf die Unterseite). Bilder wurden vom
      Nutzer bereits per Plesk hochgeladen. ZIP wurde bereitgestellt, Nutzer
      hat per FTP deployed. Deployment abgeschlossen.
- [x] Vollständige SEO-Analyse des aktuellen Stands durchgeführt (Startseite,
      erfolge.html, Impressum, Datenschutz, robots.txt, sitemap.xml,
      JSON-LD). Ergebnis: technisches Fundament ist bereits sehr stark
      (SportsClub+FAQPage-Schema, GeoCircle, selbst gehostete Fonts,
      Mobile-Optimierung); Engpass liegt nicht mehr im Code, sondern bei
      Off-Page-Signalen (siehe offene Punkte oben). Gefundene und behobene
      Kleinigkeiten:
      - erfolge.html hatte keine Open-Graph-/Twitter-Tags → ergänzt (Titel,
        Description, hero-boxer.jpeg als Share-Bild), damit Erfolge-Links
        beim Teilen (WhatsApp etc.) eine Vorschau zeigen.
      - Sitemap-URL für die Startseite hatte einen trailing Slash
        ("boxring-wetterau.de/"), Canonical/og:url nicht
        ("boxring-wetterau.de") → in sitemap.xml angeglichen.
      Geprüft und bewusst NICHT geändert: impressum.html/datenschutz.html
      haben keine meta description, aber beide sind absichtlich auf
      "noindex,follow" gesetzt – eine Description wäre dort wirkungslos.
      Meta-Keywords-Tag auf der Startseite ist SEO-technisch wirkungslos
      (von Google seit 2009 ignoriert), aber auch harmlos – bewusst
      stehengelassen statt grundlos zu löschen.
      ZIP wurde bereitgestellt, Nutzer hat per FTP deployed. Deployment
      abgeschlossen.
- [x] 2 neue Erfolge in erfolge.html + Startseiten-Teaser eingebaut,
      chronologisch neuester zuerst:
      - 13.6.2026 – "Duelle der Champions", Wiesbaden: 5 Athleten im Ring,
        5 Kämpfe/2 Siege/3 Unentschieden (Siege: Niko Kotanidis, Dominik
        Deichert; Unentschieden: Cavit Gencer, Murad Mezhidov, Yusuf
        Demiroglu). Schreibweise "Mezhidov" vom Nutzer bestätigt. Dateiname:
        erfolge-2026-06-13-duelle-champions-wiesbaden.jpg
      - 12.4.2026 – Anfängerturnier, Bruchköbel: Cavit Gencer siegt durch
        TKO in seinem 2. Kampf. Dateiname: erfolge-2026-04-12-gencer-tko.jpg
      Neue Teaser-4 auf Startseite: Duelle der Champions, Gencer-TKO,
      Guirguis-KO, Özcorapci-Deutscher-Meister-Masterboxen (bisherige
      Teaser-Einträge Leonid-Hessenmeister und U17-DM-Bronze rutschen auf
      die Unterseite). sitemap.xml lastmod für / und erfolge.html auf
      2026-07-31 aktualisiert. ZIP wurde bereitgestellt, Nutzer hat Code +
      beide Fotos per FTP/Plesk hochgeladen. Deployment abgeschlossen.
- [x] Google Business Profil Schritt für Schritt durchgegangen und optimiert:
      - Kategorien bereits korrekt gesetzt (Sportverein primär + Fitness-
        studio, Boxclub, Boxklub, Boxring als Zusatzkategorien) – Punkt war
        in Wirklichkeit schon erledigt, alte Notiz war veraltet
      - Unternehmensbeschreibung war ebenfalls schon vorhanden und gut
      - Titelbild ergänzt (hero-boxer.jpeg), weitere Fotos hochgeladen
        (membership-photo.jpg + mehrere Erfolge-Fotos) – vorher nur 1 Foto
      - Einzugsgebiet von 9 auf 18 Orte erweitert, konsistent mit Website-
        areaServed: Florstadt (61197), Reichelsheim/Wetterau (61203),
        Münzenberg (35516), Wöllstadt (61206), Nidda (63667), Altenstadt/
        Hessen (63674), Ranstadt (63691), Niddatal (61130), Echzell (61209)
        neu ergänzt
- [x] SEO-Analyse Umkreissuche (30 km um Friedberg) durchgeführt und
      Verbesserungen umgesetzt: GeoCoordinates (50.3369, 8.7561) + GeoCircle
      (geoRadius 30000m) ins SportsClub-JSON-LD ergänzt. areaServed erweitert
      um Kernorte (Nidda, Altenstadt, Hungen, Ranstadt, Niddatal, Echzell –
      durch Instagram-Hashtags als reale Reichweite belegt) im sichtbaren
      Fließtext (Standort-Sektion + FAQ) UND um Randorte (Limeshain,
      Glauburg, Ortenberg, Büdingen, Gedern, Kefenrod, Nidderau, Gießen,
      Bad Vilbel, Pohlheim, Lich, Grünberg) NUR im unsichtbaren JSON-LD, um
      Keyword-Stuffing im sichtbaren Text zu vermeiden.
      Analyse-Ergebnis: Local Pack/Google Maps wird primär durchs Google
      Business Profil entschieden (Kategorie, Fotos, Rezensionen –
      wichtigster noch offener Hebel, siehe GBP-Punkt), Website-Content
      betrifft eher die organische Websuche.
      Deployment abgeschlossen, live bestätigt.
- [x] Google Fonts selbst gehostet statt live von fonts.googleapis.com/
      fonts.gstatic.com geladen (DSGVO-Absicherung, kein IP-Transfer an
      Google mehr beim Seitenaufruf). Anton (400) + Inter (Variable Font,
      deckt 400–800 in einer Datei ab) als woff2 heruntergeladen, liegen
      unter assets/fonts/ (anton-latin-400.woff2, inter-latin-variable.woff2).
      @font-face lokal in index.html + erfolge.html eingebunden, Google-
      Fonts-<link>-Tags entfernt. Datenschutzerklärung angepasst: Absatz zu
      Google Fonts entfernt (nur noch Google Maps als externe Einbindung
      genannt). Deployment abgeschlossen (assets/fonts/ hochgeladen), live
      bestätigt.
- [x] "Erfolge"-Sektion umgesetzt: Instagram-Iframe-Galerie auf der Startseite
      durch echte <img>-Karten ersetzt (5 Erfolge chronologisch von Nutzer
      erhalten, Bilder via Plesk-Upload auf boxring-woelfersheim.de/assets/
      + Weiterleitung an Claude, da Chat-Uploads nicht als Datei zugänglich waren).
      Neueste 4 als Teaser auf Startseite (#erfolge, Nav-Link "Instagram" →
      "Erfolge"), alle 5 vollständig + neuestes zuerst auf neuer Unterseite
      erfolge.html (in sitemap.xml aufgenommen). Reiner Profil-Link
      "@boxringwetterau" im Hero-Bereich bewusst erhalten (kein Iframe mehr).
      Datenschutzerklärung angepasst: Absatz zu Instagram-Einbettungen entfernt,
      da keine Iframes mehr geladen werden.
      Bild-Dateinamen (liegen als JPEG unter assets/, Schema
      erfolge-JJJJ-MM-TT-kurzname.jpg):
      - erfolge-2020-03-08-moskoglo.jpg (nur auf Unterseite)
      - erfolge-2020-08-15-training-dm2021.jpg
      - erfolge-2021-07-03-moskoglo-brueder.jpg
      - erfolge-2021-09-15-gutmann-mezhidov.jpg
      - erfolge-2021-09-25-mezhidov-vizehessenmeister.jpg
      Deployment abgeschlossen: ZIP wurde bereitgestellt, Nutzer hat per FTP
      hochgeladen und live bestätigt (30.7.2026)
- [x] 2. Batch mit 5 weiteren Erfolgen (Vadim Moskoglo) in erfolge.html +
      Startseiten-Teaser eingebaut, chronologisch neuester zuerst. Neue
      Teaser-4 auf Startseite: Duelle der Champions, Deutscher Meister,
      DM-Teilnahme, Worms (bisheriger Teaser-Eintrag Hessenmeisterschaft/
      Mezhidov rutscht auf die Unterseite). Bild-Dateinamen (noch vom Nutzer
      per Plesk hochzuladen):
      - erfolge-2021-10-09-moskoglo-nrwcup.jpg
      - erfolge-2021-11-14-moskoglo-worms.jpg
      - erfolge-2021-11-21-moskoglo-dm-teilnahme.jpg (Trainer erwähnt)
      - erfolge-2021-12-21-moskoglo-deutscher-meister.jpg (Trainer erwähnt)
      - erfolge-2022-02-27-moskoglo-duelle-champions.jpg
      Deployment abgeschlossen (inkl. Fix object-position:top gegen
      abgeschnittene Köpfe bei Hochformat-Fotos), live bestätigt
- [x] 3. Batch mit 5 weiteren Erfolgen in erfolge.html + Startseiten-Teaser
      eingebaut, chronologisch neuester zuerst. Neue Teaser-4 auf Startseite:
      Guirguis-KO, Özcorapci-Masterboxen-DM, Leonid-Hessenmeister, U17-DM-
      Bronze. Bisherige Teaser-Einträge (Duelle der Champions, Moskoglo-DM,
      DM-Teilnahme, Worms) rutschen auf die Unterseite. Bild-Dateinamen
      (noch vom Nutzer per Plesk hochzuladen):
      - erfolge-2022-04-29-moskoglo-dm-bronze.jpg (Trainer erwähnt)
      - erfolge-2022-05-07-moskoglo-wolff-tko.jpg
      - erfolge-2022-06-06-moskoglo-hessenmeister.jpg (Trainer erwähnt)
      - erfolge-2023-01-30-oezcorapci-deutscher-meister-masterboxen.jpg
        (Ahmet Özcorapci selbst als Athlet, nicht nur als Trainer erwähnt)
      - erfolge-2023-11-18-guirguis-masterboxen-ko.jpg (1. Vorsitzender
        Philipp Guirguis, kein großes Turnier daher kein Trainer-Verweis;
        Katja Insenbiel aus dem Originaltext bewusst weggelassen, da sie
        nicht auf dem Foto zu sehen ist)
      Deployment abgeschlossen, live bestätigt.
- [x] Vereinsregisternummer + Amtsgericht ermittelt (VR 1020, Amtsgericht Friedberg)
      und in impressum.html eingetragen – auch für Anfrage an Strothmann IT
      bzgl. boxring-woelfersheim.de verwendbar
- [x] Impressum + Datenschutzerklärung erstellt
- [x] Telefonnummer + JSON-LD (telephone, priceRange, areaServed) ergänzt
- [x] FAQ-Sektion + FAQPage-Schema
- [x] Noscript-Fallback für nicht-JS-Crawler
- [x] Hero-Bild als echtes <img> mit Alt-Text, Instagram-Iframes lazy geladen
- [x] Footer mit NAP-Daten + Fix: Footer/Impressum lag zunächst außerhalb des
      vom Framework geladenen Templates → behoben (PR #2)
- [x] Vereinsname konsistent auf "Boxring Wetterau 1983 e.V." korrigiert,
      Website + GBP abgeglichen (PR #3)
- [x] GBP-Unternehmensbeschreibung + Rezensions-Anfrage-Text formuliert
- [x] Domain-Problem erkannt: boxring-wetterau.de lief nur als Alias unter
      boxring-woelfersheim.de (Duplicate-Content-Risiko) → Nachricht an Hoster
      formuliert und verschickt (Domain-Tausch)
- [x] Mobiles Burger-Menü Fix: Event-Delegation statt direktem Listener,
      da Framework Header bei Re-Render neu erzeugt (PR #4)
- [x] Alle Deploys laufen manuell per FTP (kein Auto-Deploy) – ZIP-Dateien
      wurden jeweils bereitgestellt
- [x] Verein im Vereinsverzeichnis der Stadt Friedberg angemeldet (Name,
      Beschreibung, Logo als Bild inkl. Alt-Text und Copyright, Adresse
      Trainingshalle, Telefon, Website eingetragen)

## Hinweis für Claude (Workflow)
- Nach jeder vom Nutzer genehmigten Code-Änderung (commit + push erfolgt):
  immer automatisch eine Pull Request erstellen, ohne extra nachzufragen.

## Hintergrund-Infos für nächste Session
- Deploy-Workflow: Code lebt im GitHub-Repo aozcorap/Webseiten, Branch master.
  Live-Seite läuft NICHT über GitHub Pages, sondern eigenes Hosting
  (Strothmann IT, Plesk) – Uploads erfolgen manuell per FTP durch den Nutzer.
- Framework-Eigenheit: Seite nutzt ein React-basiertes "dc-runtime"-Template-
  System (x-dc/sc-for/sc-if in support.js). Alles, was on-page sichtbar/
  crawlbar sein soll, MUSS innerhalb von <x-dc>...</x-dc> stehen (String-
  Grenze, kein DOM-Parsing) – sonst wird es beim zweiten JS-Render-Durchlauf
  rausgefiltert.
- Wichtige technische Einschränkung: Bilder, die der Nutzer im Chat einfügt,
  sind für Claude NICHT als Datei zugänglich (nur visuell sichtbar). Für neue
  Erfolge-Fotos muss der Nutzer sie selbst per Plesk-File-Manager in
  boxring-woelfersheim.de/httpdocs/assets/ hochladen (aktuelle Hauptdomain,
  Domain-Tausch zu boxring-wetterau.de steht noch aus); Claude referenziert
  sie dann per Dateiname im Code.
- Telefonnummer: 0173-2609937
- Adresse Trainingshalle: Maria-Montessori-Weg 2, 61169 Friedberg
- Impressum-Anschrift: Ahmet Özcorapci, Hospitalgasse 36d, 61169 Friedberg
