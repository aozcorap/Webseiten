# Offene Punkte – Boxring Wetterau SEO/AEO (Stand 2026-07-29, Ende Session)

## Offen
- [ ] Deployment der neuen Erfolge-Seite: ZIP erstellen und Nutzer per FTP
      hochladen lassen, sobald final geprüft (siehe "Bereits erledigt" für Details)
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
      - erfolge-2020-03-08-moscoglo.jpg (nur auf Unterseite)
      - erfolge-2020-08-15-training-dm2021.jpg
      - erfolge-2021-07-03-moscoglo-brueder.jpg
      - erfolge-2021-09-15-gutmann-mezhidov.jpg
      - erfolge-2021-09-25-mezhidov-vizehessenmeister.jpg
      Noch offen: Deployment (ZIP + FTP-Upload, siehe "Offen")
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
- Telefonnummer: 0173-2609937
- Adresse Trainingshalle: Maria-Montessori-Weg 2, 61169 Friedberg
- Impressum-Anschrift: Ahmet Özcorapci, Hospitalgasse 36d, 61169 Friedberg
