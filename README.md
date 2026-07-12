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

## Offene Punkte (boxring-wetterau.de v2)

- [ ] Trainingsplan-Fotos (Sparring, Grundlagentraining) besorgen
- [ ] Galerie-Fotos für die übrigen Plätze
- [ ] Instagram-Video-Einbindung neu angehen (Download + Self-Hosting statt Embed)
- [ ] Nach Fertigstellung: `index-v2.html` gegen `index.html` tauschen und live schalten
