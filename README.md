# Webseiten

Sammlung statischer Websites (reines HTML/CSS/JS, kein Framework).

## Projekte

### [ozcorapci.de](ozcorapci.de/index.html)
Portfolio-Website, Sidebar-Layout, Dark Mode mit violettem Akzent.

- Design-Tokens, Struktur und Inhalte: [FRAMER-MIGRATION.md](ozcorapci.de/FRAMER-MIGRATION.md) (Konzept für einen möglichen Framer-Umzug, aktuell nicht umgesetzt)
- Aktueller Stand: gepflegte, handgecodete Live-Version, keine Migration geplant

### [boxring-wetterau.de](boxring-wetterau.de/index.html)
Vereins-Website Boxring Wetterau e.V., Rot/Gold-Farbschema.

- **`index.html`** — aktuelle Live-Version (helles Layout)
- **`index-v2.html`** — Redesign-Entwurf: dunkles, kontrastreiches Layout nach Vorbild moderner Boxstudio-Seiten (u.a. IBRA Boxing, Executive Sports Club). Noch nicht live geschaltet.
  - Enthält ein reales Foto (`assets/img/bandagen.jpg`) an mehreren Stellen (Hero, Über uns, CTA)
  - Trainingsplan-Karten (Sparring/Grundlagen) und ein Teil der Galerie sind noch **Platzhalter** — echte Trainingsfotos fehlen noch
  - Instagram-Einbindung (`@boxringwetterau`) ungelöst: offizielle Embeds zeigen zwingend Instagram-Rahmen (Like/Kommentar-Leiste), lassen sich nicht auf reines Video reduzieren. Nächster Versuch: Videos selbst hosten statt einbetten — offen für nächste Session.

### [buerokollege-ai](buerokollege-ai/index.html)
Landingpage "BüroKollege.AI" — 30 Tage kostenlos testen, digitaler Kollege für Handwerksbetriebe (KI-Agent für Postfach/WhatsApp/Rechnungen).

- **Aktuell gültige Version** — dunkles Design mit Farbverlauf (dunkel bei Problem-Sektion → hell bei Lösung/Formular), Chat-Demo im Hero (Sprachnachricht → Angebots-PDF, als Beispiel gekennzeichnet)
- Formular sendet noch nicht (kein Endpunkt angeschlossen, zeigt Fallback-E-Mail)
- Impressum/Datenschutz fehlen noch
- Wettbewerber: kiwerk.ai (echtes Produkt, echte Kundenstimmen, offene Preise) — eigene Positionierung dagegen noch offen

### [everlast-entwuerfe](everlast-entwuerfe/) & [everlast-ai-landingpage](everlast-ai-landingpage/)
Vorstufen zu buerokollege-ai: drei Design-Entwürfe (dunkel/hell/kräftig) sowie eine erste, verworfene Überarbeitung. Nicht mehr aktiv gepflegt, siehe [.claude/STATE.md](.claude/STATE.md) für Details.

## Offene Punkte (boxring-wetterau.de v2)

- [ ] Trainingsplan-Fotos (Sparring, Grundlagentraining) besorgen
- [ ] Galerie-Fotos für die übrigen Plätze
- [ ] Instagram-Video-Einbindung neu angehen (Download + Self-Hosting statt Embed)
- [ ] Nach Fertigstellung: `index-v2.html` gegen `index.html` tauschen und live schalten
