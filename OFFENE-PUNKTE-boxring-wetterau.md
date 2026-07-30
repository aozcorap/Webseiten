# Offene Punkte – Boxring Wetterau SEO/AEO (Stand 2026-07-29, Ende Session)

## Offen
- [ ] Deployment des 3. Erfolge-Batches (5 neue Erfolge, siehe "Bereits
      erledigt"): Nutzer muss noch die 5 Bilddateien per Plesk hochladen,
      danach neue ZIP bereitstellen und hochladen lassen
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
- [ ] Google Business Profil weiter optimieren:
      - Kategorie ergänzen (z. B. "Boxsportverein"/"Sportverein" + "Fitnessstudio")
      - Mehr Fotos hochladen (Halle, Training, Vorstand, Logo – aktuell nur 1 Foto)
      - Rezensionen aktiv einsammeln (aktuell nur 1 Rezension, größter Ranking-Hebel;
        Text für Rezensions-Anfrage wurde bereits formuliert)
      - Unternehmensbeschreibung einfügen (Text wurde bereits formuliert)
- [ ] NAP-Einträge (Name/Adresse/Telefon) in weiteren Verzeichnissen ergänzen:
      HABV-Verbandsseite, DOSB-Vereinssuche, LSB Hessen Vereinsverzeichnis
      (Stadt Friedberg Vereinsverzeichnis bereits erledigt, siehe unten;
      Das Örtliche/Gelbe Seiten bewusst rausgenommen – primär Firmenverzeichnisse,
      für einen Verein wenig SEO-Nutzen)
- [ ] Google Search Console: Domainverifizierung über GoDaddy abschließen,
      danach sitemap.xml (https://www.boxring-wetterau.de/sitemap.xml) einreichen
- [ ] (optional, DSGVO-Absicherung) Google Fonts selbst hosten statt live von
      fonts.googleapis.com laden

## Bereits erledigt
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
      Noch offen: Deployment (siehe "Offen")
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
