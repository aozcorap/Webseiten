# Technische SEO-Issues — aralia.de (Live-Seite)

Analyse der echten Live-Seite [aralia.de](https://aralia.de) (Aralia Ballroom, Florstadt) — technische Probleme, die aktiv Google-Ranking und dadurch Besucher/Umsatz kosten. Verifiziert per Quellcode-, Header- und Ladegrößen-Prüfung, nicht geraten.

Bezieht sich auf die **externe Live-Seite des Kunden**, nicht auf den Redesign-Entwurf in diesem Verzeichnis ([index.html](index.html)).

---

## 🔴 High Priority

### 1. ~227 MB unkomprimierte GIF-Dateien zerstören Ladezeit und Ranking
**Kategorie:** Performance / Core Web Vitals
**Befund:** Allein im Events-Bereich der Startseite liegen 11 animierte GIFs, die zusammen ca. 227 MB groß sind (Einzeldatei bis zu 52,9 MB).
**Warum das Ranking & Umsatz kostet:** Google bewertet Ladezeit (insbesondere LCP) als direkten Rankingfaktor, vor allem bei Mobile-First-Indexierung. Auf mobilen Verbindungen dauert das Laden dieser Sektion teils >30s — die meisten Besucher brechen vorher ab. Jeder Absprung erhöht die Bounce-Rate, was Google als Qualitätssignal negativ wertet.
**Empfehlung:** GIFs durch komprimiertes MP4/WebM oder optimierte WebP-Sequenzen ersetzen (Zielgröße: einzelne MB statt zweistelliger MB-Beträge pro Datei).

### 2. Keine Server-Kompression (gzip/brotli) aktiv
**Kategorie:** Performance / Server-Konfiguration
**Befund:** Im HTTP-Response-Header des Hauptdokuments fehlt `content-encoding` vollständig.
**Warum das Ranking & Umsatz kostet:** Unkomprimierte Auslieferung verlängert jede Anfrage unnötig — multipliziert sich bei 65+ einzelnen CSS/JS-Dateien. Wirkt sich direkt auf Core Web Vitals (LCP, TTFB) aus.
**Empfehlung:** gzip oder brotli serverseitig aktivieren (meist reine Konfigurationsänderung ohne Codeänderung).

### 3. Keine Meta-Description im `<head>`
**Kategorie:** Meta-Daten / Crawlability
**Befund:** Das `<meta name="description">`-Tag fehlt auf der Startseite komplett.
**Warum das Ranking & Umsatz kostet:** Google generiert den Suchergebnis-Snippet automatisch aus beliebigem Seiteninhalt — meist unpassend. Direkter Effekt auf die Klickrate (CTR): Nutzer klicken eher auf einen Wettbewerber mit ansprechenderer Beschreibung, selbst bei gleichem Ranking.
**Empfehlung:** Für Startseite und alle Unterseiten (`/pakete/`, `/catering/`) je eine individuelle, verkaufsstarke Meta-Description (ca. 150–160 Zeichen) hinterlegen.

### 4. Keine strukturierten Daten (Schema.org LocalBusiness/EventVenue)
**Kategorie:** Meta-Daten / Rich Snippets
**Befund:** Keine einzige `application/ld+json`-Auszeichnung im Quellcode.
**Warum das Ranking & Umsatz kostet:** Ohne strukturierte Daten kann Google keine Rich-Snippets anzeigen (Sternebewertung, Adresse, Öffnungszeiten direkt im Suchergebnis) — senkt die Klickrate gegenüber Wettbewerbern. Lokale Suchanfragen ("Hochzeitslocation Wetterau") profitieren besonders von `LocalBusiness`-Markup für die Google-Maps-/Local-Pack-Sichtbarkeit.
**Empfehlung:** `LocalBusiness`- bzw. `EventVenue`-Schema mit Adresse, Öffnungszeiten, Bewertungen ergänzen; für FAQ-artige Inhalte zusätzlich `FAQPage`-Schema.

---

## 🟡 Medium Priority

### 5. Keine Open-Graph-/Twitter-Card-Tags
**Kategorie:** Meta-Daten / Social Sharing
**Befund:** Keine `og:title`, `og:description`, `og:image` oder Twitter-Card-Tags vorhanden.
**Warum das kostet:** Kein direkter Google-Rankingfaktor, aber ein Umsatzfaktor: Wird der Link geteilt (bei Hochzeiten üblich), erscheint kein Bild/Text, nur ein nackter Link — wird nachweislich seltener angeklickt.
**Empfehlung:** Open-Graph- und Twitter-Card-Tags mit ansprechendem Bild und prägnantem Text ergänzen.

### 6. Title-Tag unnatürlich formuliert, schwächt Klickrate
**Kategorie:** Meta-Daten
**Befund:** Aktueller Title: „Aralia Ballroom – IHRE EVENTLOCATION ARALIA – BALLROOM IN FLORSTADT" — stichwortartig aneinandergereiht.
**Warum das kostet:** Ein unnatürlicher Title senkt die Klickrate in den Suchergebnissen, selbst bei gutem Ranking. Wiederholung von "Aralia"/"Ballroom" wirkt wie Keyword-Stuffing.
**Empfehlung:** Natürlich formulierter Title, z. B. „Aralia Ballroom Florstadt — Hochzeiten, Firmenevents & Galas bis 450 Gäste".

### 7. Keine Cache-Control-Header gesetzt
**Kategorie:** Performance / Server-Konfiguration
**Befund:** Response-Header enthalten kein `Cache-Control`. Wiederkehrende Besucher laden alle Assets bei jedem Besuch neu.
**Warum das kostet:** Wirkt sich auf wiederholte Ladezeiten aus (Page-Experience-Signal). Interessenten, die die Seite mehrfach besuchen, erleben jedes Mal die volle Ladezeit erneut.
**Empfehlung:** Sinnvolle Cache-Control-Header für statische Assets setzen (`max-age` im Wochen-/Monatsbereich).

### 8. Kaum Lazy-Loading/srcset bei Bildern
**Kategorie:** Performance / Bilder
**Befund:** Nur 9 von vielen Bildern haben `loading="lazy"`, nur 11 ein `srcset`.
**Warum das kostet:** Erhöht die initiale Ladezeit unnötig, besonders mobil — direkter Effekt auf LCP. Verschwendet zudem mobiles Datenvolumen der Besucher.
**Empfehlung:** Konsequent `loading="lazy"` unterhalb des ersten Viewports sowie `srcset`/`sizes` für alle Galerie-/Content-Bilder ergänzen.

### 9. Großteil der Bilder ohne Alt-Text
**Kategorie:** Content / Bilder-SEO
**Befund:** Von 15 stichprobenartig geprüften Bildern hatten 10 keinen (oder leeren) `alt`-Text.
**Warum das kostet:** Google kann Bilder ohne Alt-Text schlechter in der Bildersuche einordnen — ein Kanal mit viel Suchvolumen bei Hochzeitsthemen. Zusätzlich ein Barrierefreiheits-Mangel.
**Empfehlung:** Alle Content-Bilder (insbesondere Galerie/Catering) mit prägnanten, beschreibenden Alt-Texten versehen.

### 10. 65 unminifizierte CSS/JS-Dateien (Elementor-Bloat)
**Kategorie:** Performance / Code-Struktur
**Befund:** 65 separate CSS/JS-Dateien, kein erkennbares Bundling/Minifying. Über 1.800 Elementor-Markup-Vorkommen im HTML.
**Warum das kostet:** Jede Datei bedeutet einen zusätzlichen HTTP-Request. Ein aufgeblähter DOM verlangsamt zusätzlich das Rendering (Time to Interactive).
**Empfehlung:** Elementor-eigene Optimierungsfunktionen (CSS/JS-Kombinierung) aktivieren, alternativ Performance-Plugin zur Asset-Optimierung einsetzen.

---

## 🟢 Low Priority

### 11. Sitemap-Erreichbarkeit prüfen (wp-sitemap.xml)
**Kategorie:** Crawlability
**Befund:** `robots.txt` verweist korrekt auf `https://aralia.de/wp-sitemap.xml`. Ein Abruf über den generischen `/sitemap.xml`-Pfad lieferte keinen Inhalt.
**Warum das kostet:** Eine nicht korrekt eingereichte/erreichbare Sitemap kann verzögerte oder fehlende Indexierung neuer Seiten bedeuten.
**Empfehlung:** `wp-sitemap.xml` in der Google Search Console einreichen/prüfen, Indexierungsstatus aller Unterseiten kontrollieren.

### 12. Website-Textmenge/Themenbreite insgesamt dünn
**Kategorie:** Content
**Befund:** Startseite ca. 2.800 Wörter Fließtext, stark bildlastig. `/catering/` nur ca. 150 Wörter bei 17–18 Fotos, `/pakete/` beschreibt nur ein einziges generisches Paket.
**Warum das kostet:** Wenig Fließtext = wenig Angriffsfläche für thematisch relevante Keywords (Firmenevents, Seminare, Catering-Details). Google hat weniger Text, um thematische Relevanz zu erkennen.
**Empfehlung:** Unterseiten inhaltlich ausbauen, insbesondere für Firmenevents/Seminare/Messen/Abibälle (siehe Redesign-Entwurf [index.html](index.html) in diesem Verzeichnis).

### 13. Fehlende Sicherheits-Header (HSTS, CSP, X-Frame-Options)
**Kategorie:** Technische Basis / Sicherheit
**Befund:** Im HTTP-Response fehlen `Strict-Transport-Security`, `Content-Security-Policy` und `X-Frame-Options`.
**Warum das kostet:** Kein direkter Rankingfaktor, aber Google berücksichtigt Sicherheitssignale im weiteren Sinne (Page Experience). Sicherheitslücken sind zudem ein Reputationsrisiko.
**Empfehlung:** Grundlegende Sicherheits-Header serverseitig ergänzen (geringer Aufwand, meist reine Konfiguration).

### 14. Fehlerhafter tel:-Link kostet direkt Anfragen
**Kategorie:** Technischer Bug / Conversion (kein Ranking-Thema, aber direkter Umsatzverlust)
**Befund:** Klickbarer Telefonlink fehlerhaft kodiert: `tel:+49060419694969%20` — führendes „0" nach Landesvorwahl, kodiertes Leerzeichen am Ende.
**Warum das kostet:** Auf manchen Mobilgeräten funktioniert das direkte Antippen-zum-Anrufen dadurch nicht zuverlässig — bei einer spontanen Kaufentscheidung ein unnötig verlorener Kontakt.
**Empfehlung:** Link korrigieren zu `tel:+4906041969496` (5-Minuten-Fix).

---

## Kurz-Priorisierung
Die vier High-Priority-Punkte (GIF-Größe, Kompression, Meta-Description, strukturierte Daten) sind der größte Hebel und lassen sich unabhängig vom großen Design-Redesign schnell und risikoarm umsetzen.
