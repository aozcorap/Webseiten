# Aralia Ballroom — Redesign-Entwurf

Interaktiver Redesign-Vorschlag für die Website des Aralia Ballroom (Florstadt), erstellt als Pitch-Grundlage für ein Kundengespräch.

## Worum es geht

Die aktuelle Live-Seite ([aralia.de](https://aralia.de)) verkauft den Hero-Bereich (Video, Helikopter-Ankunft, Luxus-Ambiente) sehr stark, ist inhaltlich aber fast ausschließlich auf Hochzeiten ausgelegt — Firmenevents, Seminare, Messen/Galas und Abibälle bekommen jeweils nur ein bis zwei Sätze und kein eigenes Paket.

Dieser Entwurf übernimmt Designsprache, Farben und Typografie eines bestehenden Konzepts 1:1, ergänzt aber:

- eine neutrale Hero-Botschaft mit direkter Weiche zu allen fünf Anlässen
- eine eigenständige Sektion pro Anlass (Hochzeiten, Firmenevents & Seminare, Messen & Galas, Abibälle) mit passenden Nutzenargumenten
- eigene Pakete pro Zielgruppe statt eines einzigen, wedding-formulierten Pakets
- ein Kontaktformular, dessen Ansprache sich live an den gewählten Anlass anpasst
- eine eingebaute "Warum dieser Entwurf"-Sektion mit Vorher/Nachher-Argumentation für das Kundengespräch

## Technisch

- Einzelne, eigenständige `index.html` — keine externen Requests (Schriften und Bilder sind eingebettet), läuft offline und lässt sich beliebig hosten (z. B. GitHub Pages)
- Bildmaterial: lizenzfreie Platzhalterfotos (Unsplash-Lizenz), thematisch passend zu den jeweiligen Anlässen — vor einem echten Launch durch eigenes Bildmaterial des Veranstalters zu ersetzen
- Reines HTML/CSS/JS, kein Framework, kein Build-Schritt

## Status

Reiner Verkaufs-/Pitch-Entwurf, kein Produktivsystem. Enthält Platzhalter-Kontaktdaten und keine rechtlich vollständigen Impressum-/Datenschutz-Seiten.
