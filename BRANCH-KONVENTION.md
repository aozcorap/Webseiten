# Konvention für Branch-Namen in diesem Repo

Dieses Repo enthält mehrere unabhängige Website-Projekte in eigenen
Unterverzeichnissen (`ozcorapci.de`, `boxring-wetterau.de`, `buerokollege-ai`,
`aralia-ballroom.de`, `cc-dienstleistungen-friedberg.de`, `brw-sommerfest`,
...). Ohne erkennbaren Bezug zum Projekt im Branch-Namen ist die
Branch-Übersicht auf GitHub kaum zu überblicken.

## Wie Branch-Namen entstehen

Branch-Namen (`claude/...`) werden bei jeder neuen Claude-Code-Session
automatisch aus der ersten Nachricht generiert – nicht manuell vergeben.
Ein festes, technisch erzwungenes Präfix-Schema (z. B. `boxring/...` als
von GitHub gruppierter Ordner) lässt sich dadurch nicht garantieren.

**Was aber zuverlässig funktioniert:** Wird der Projektname als erstes Wort
der Aufgabenbeschreibung genannt, landet er automatisch im generierten
Branch-Slug. Beispiele aus der bisherigen Historie:

- "Boxring: ..." → `claude/boxring-success-story-869fo8`
- "Aralia: ..." → `claude/aralia-events-website-l1l5oi`
- "BüroKollege: ..." → `claude/buerokollege-lead-conversion-24gowd`

## Empfehlung für neue Sessions

Beim Start einer neuen Aufgabe den Projektnamen (Ordnername oder
gebräuchliche Kurzform) vorne in die erste Nachricht schreiben:

| Projekt | Kurzform für den Branch-Slug |
|---|---|
| `boxring-wetterau.de` | `boxring` |
| `aralia-ballroom.de` | `aralia` |
| `buerokollege-ai` | `buerokollege` |
| `cc-dienstleistungen-friedberg.de` | `cc-dienstleistungen` |
| `ozcorapci.de` | `ozcorapci` |
| `brw-sommerfest` | `sommerfest` oder `brw-sommerfest` |

Betrifft eine Aufgabe kein einzelnes Kundenprojekt (z. B. Repo-Pflege,
Skills, `CLAUDE.md`), reicht eine kurze, eigenständige Beschreibung ohne
Projekt-Präfix.

## Bestehende Branches

Ältere Branches folgen diesem Schema bereits größtenteils zufällig, da der
Projektname meist ohnehin Teil der Aufgabenbeschreibung war. Es wurde
nichts rückwirkend umbenannt (ein Branch-Rename ist in Git technisch ein
neuer Branch mit eigener Historie) – die Konvention gilt ab sofort für neue
Branches.
