# Offene Punkte — aralia.de

Neu erstellte, hochwertige Landingpage für Aralia Events (Hochzeiten & Events,
Signature-Feature: Ankunft des Brautpaars per Helikopter). Reines HTML/CSS/JS,
kein Framework, keine externen Font-/Script-Requests.

## Struktur

- `index.html` — One-Pager: Hero, Leistungen, Helikopter-Feature, Ablauf, Referenzen, Locations, Kontaktformular
- `impressum.html`, `datenschutz.html` — Platzhalter, rechtlich noch nicht vollständig (siehe TODO-Hinweise im jeweiligen Dokument)
- `css/styles.css`, `js/main.js`

## Vor dem Go-Live zu klären

- **Impressum/Datenschutz**: echte Firmierung, Anschrift, Vertretungsberechtigte, ggf. Handelsregister/USt-IdNr. eintragen (§5 TMG). Aktuell nur Platzhalter.
- **Kontakt**: E-Mail-Adresse `info@aralia.de` ist angenommen, nicht verifiziert. Telefonnummer fehlt bewusst (keine echte Nummer bekannt).
- **Formular**: sendet aktuell nur per `mailto:`-Weiterleitung (kein Server-Endpunkt), analog zum aktuellen Stand von buerokollege-ai.
- **Bildmaterial**: Website verwendet bewusst keine echten Fotos (keine Rechte/Quellen vorhanden) — Hero, Locations und Helikopter-Feature sind mit CSS-Farbverläufen bzw. einer eigenen SVG-Illustration gestaltet. Vor Launch durch echtes Bild-/Videomaterial (Hochzeiten, Locations, Helikopter-Landung) ersetzen.
- **Locations-Sektion**: nennt bewusst nur Kategorien (Schlösser, Weingüter, Seenähe, Alpine Chalets), keine konkreten Partnerlocations — bei realen Partnerschaften ergänzen.
- **Referenzen**: exemplarische, nicht verifizierte Zitate — vor Launch durch echte, freigegebene Kundenstimmen ersetzen oder entfernen.
- **Domain/Deploy**: noch kein Deploy-Ziel/Hosting für aralia.de hinterlegt.
