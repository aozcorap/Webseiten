# Framer-Migrationskonzept — ozcorapci.de

Aufbereitung der bestehenden Seite für den 1:1-Aufbau in Framer. Kein Code nötig — nur Struktur, Texte und Design-Werte zum Übertragen.

---

## 1. Template-Wahl

Suche im Framer-Marktplace nach einem Template aus der Kategorie **"Portfolio" / "Personal Site" / "Resume"** mit:
- Sidebar- oder Zwei-Spalten-Layout (Profil links, Content rechts)
- Dark Mode als Basis (die Seite ist aktuell dunkel gestaltet)

Suchbegriffe in Framer: `portfolio dark`, `resume sidebar`, `personal cv`.

---

## 2. Design-Tokens (Farben, Schrift, Radien)

In Framer unter **Design Panel → Colors/Fonts** als Presets anlegen:

| Rolle | Wert | Verwendung |
|---|---|---|
| Hintergrund | `#0a0a0f` | Seite |
| Fläche (Sidebar/Karten) | `#15151c` | Sidebar, Cards |
| Fläche 2 (innere Boxen) | `#1c1c25` | Stat-Boxen, Icon-Cards |
| Rahmen | `#2a2a35` | Borders |
| Text | `#f2f2f5` | Haupttext |
| Text gedämpft | `#9a9aa8` | Sekundärtext |
| Akzent (Violett) | `#8b7cf6` | Links, Buttons, Icons |
| Akzent Hover | `#a394f9` | Hover-Zustand |

**Schrift:** Inter (Haupttext), JetBrains Mono (für Zeitangaben/Tags — optional, Inter reicht auch).

**Eckenradius:** Karten/Sidebar `16px`, Buttons/Boxen `10px`, kleine Elemente `6px`.

---

## 3. Seitenstruktur

### Sidebar (links, sticky)
- Profilfoto (rund, 96×96px) → `assets/img/profilbild.jpg`
- Name: **Ahmet Özcorapci**
- Rolle: *Senior Program Manager — IT Infrastructure Transformation*
- 3 Badges: `Program Management` · `Cloud & Infrastructure` · `AI / Python`
- Navigation: Über mich · Werdegang · Skills · Projekt · Kontakt
- Kontaktblock:
  - E-Mail: ahmet@ozcorapci.de
  - LinkedIn: /in/ahmet-özcorapci
  - Sprachen: Deutsch, Englisch, Türkisch

*In Framer: eigene Sidebar-Section, bei Mobile als aufklappbares Menü (Framer-Komponente "Accordion" oder "Toggle" nutzen, falls Template keine hat).*

### Section 1 — Über mich
> Seit über zwei Jahrzehnten begleite ich IT-Infrastruktur- und Transformationsprojekte im Enterprise-Umfeld — von der Planung bis zur Stabilisierung im laufenden Betrieb. Aktuell verantworte ich bei dormakaba ein globales Infrastruktur- und Cloud-Transformationsprogramm mit klarer Steuerung, Entscheidungsfindung und Risikomanagement, ohne die operative Umsetzung selbst zu übernehmen. Meine Stärke liegt darin, komplexe Transformationen in einen stabilen, skalierbaren Betrieb unter realen Enterprise-Bedingungen zu überführen. Parallel dazu vertiefe ich mein technisches Verständnis praktisch: mit einem selbst entwickelten, KI-gestützten Softwareprojekt.

Dipl. Informatiker (Technische Hochschule Mittelhessen, 1990) · ITIL Foundation · PRINCE2 Foundation

**Stat-Grid (3 Boxen):**
| Zahl | Label |
|---|---|
| 1.600+ | Server |
| >90 | Standorte |
| ~3 Mio. € | Budget |

### Section 2 — Werdegang (Timeline)
Framer-Baustein: "Timeline" oder "Vertical List" mit Linie + Punkten.

| Zeitraum | Firma | Rolle | Beschreibung |
|---|---|---|---|
| 09/2020 – heute | dormakaba | Senior Program Manager – IT Infrastructure Transformation | Gesamtverantwortung für ein globales Infrastruktur- und Cloud-Transformationsprogramm: Migration von Legacy-Infrastrukturen in Azure- und Hybrid-Architekturen, Steuerung internationaler Infrastruktur-, Netzwerk- und Cloud-Teams sowie Provider, technische Deep Dives zur Risikoanalyse, Aufbau von Governance- und Reportingstrukturen, direkte Zusammenarbeit mit CIO und Senior Management. *(Stat-Pills: 1.600+ Server, >90 Standorte, ~3 Mio. € Budget — aktueller Eintrag hervorgehoben)* |
| 2017 – 2020 | Vorwerk | Senior IT Project Manager | Steuerung eines europaweiten IT-Programms (Dokumentenmanagement), Leitung komplexer IT-Projekte in Konzernumgebung. |
| 2014 – 2017 | ewocon GmbH | Associated Senior Project Manager | Umsetzung von Portfolio- und Projektmanagementlösungen, Analyse- und Solution-Design-Workshops. |
| 2013 – 2014 | Braas Monier | IT Shared Service Manager | Steuerung von IT-Services und SLAs, Service Transition und Betriebsstabilisierung. |
| 2012 | Hewlett Packard | Infrastructure Project Manager | Leitung von Infrastruktur- und Migrationsprojekten. |
| 2000 – 2012 | Vodafone Umfeld | Project Manager | Infrastruktur-, Plattform- und Transformationsprojekte in Enterprise-Umgebungen. |

### Section 3 — Skills (2×2 Grid)
| Kürzel | Titel | Beschreibung |
|---|---|---|
| PM | Programm- & Projektmanagement | Enterprise Scale, IT Governance, Risk & Escalation Management, Service Transition & Operations Stabilization, Vendor & Provider Management |
| IT | IT-Infrastruktur | Cloud & Infrastructure Transformation (Azure / Hybrid IT), Netzwerk & Connectivity, Unified Communications & Plattformmigration |
| AI | Technisch & AI | Python, Claude Code / Anthropic API, Grundlagen moderner Daten- und KI-Use-Cases im Cloud-Kontext |
| CIO | Stakeholder | CIO Advisory & Stakeholder Management, direkte Zusammenarbeit mit Senior Management |

### Section 4 — Persönliches Projekt: Trading-Bot
> Neben der Programmleitung bei dormakaba entwickle ich in Eigenregie einen automatisierten Trading-Bot — ein praktisches Projekt, um KI-gestützte Softwareentwicklung nicht nur zu steuern, sondern selbst zu betreiben.

**Ansatz:** Umsetzung mit Claude Code und der Anthropic API in Python. Das Projekt ist der Ort, an dem ich Automatisierung, Datenverarbeitung und KI-gestützte Entwicklungs-Workflows praktisch erprobe — als direkte Ergänzung zu meiner steuernden Rolle in Cloud- und Infrastrukturprogrammen.

Tags: `Python` · `Claude Code` · `Anthropic API`

*Hinweis (kursiv, dezent):* Weitere Details zu Architektur und Ergebnissen folgen in Kürze.

### Section 5 — Kontakt
> Interessiert an einem Austausch zu Programmleitung, IT-Transformation oder dem Trading-Bot-Projekt? Ich freue mich auf eine Nachricht.

Buttons:
- **E-Mail schreiben** (primär, gefüllt) → mailto:ahmet@ozcorapci.de
- **LinkedIn-Profil** (sekundär, outline) → LinkedIn-Link
- **CV herunterladen** (sekundär, outline) → PDF-Datei neu in Framer hochladen

Hinweistext: Hinweise zum Datenschutz finden Sie in der Datenschutzerklärung. (verlinkt)

### Footer
© 2026 Ahmet Özcorapci · Impressum · Datenschutz

---

## 4. Rechtsseiten (Impressum, Datenschutz)

Bestehen bereits als eigene HTML-Seiten (`impressum.html`, `datenschutz.html`). In Framer als zwei zusätzliche Unterseiten anlegen, Inhalte 1:1 übernehmen (Text rüberkopieren, keine Gestaltung nötig — schlichte Textseite reicht rechtlich).

---

## 5. Assets, die du hochladen musst

- `assets/img/profilbild.jpg` — Profilfoto
- `assets/img/favicon.svg` — Favicon (Framer hat eigenes Favicon-Feld in den Site-Settings)
- `assets/img/og-image.jpg` — Social-Share-Bild (Framer: SEO-Settings pro Seite)
- `assets/docs/CV_Ahmet_Oezcorapci.pdf` — CV zum Download

---

## 6. Reihenfolge zum Nachbauen (empfohlen)

1. Template wählen, Farben/Fonts als Presets setzen
2. Sidebar aufbauen (Foto, Name, Badges, Nav, Kontakt)
3. Sections 1–5 der Reihe nach mit Text aus diesem Dokument füllen
4. Assets hochladen (Foto, Favicon, CV, OG-Bild)
5. Rechtsseiten anlegen und verlinken
6. Mobile-Ansicht prüfen (Sidebar sollte oben oder als Menü erscheinen)
7. Domain `ozcorapci.de` in Framer verbinden (DNS-Einträge beim aktuellen Domain-Provider anpassen)

**Geschätzter Aufwand:** 1,5–2 Stunden, da alle Texte fertig sind und nur noch strukturiert übertragen werden müssen.
