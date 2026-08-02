# Konvention für GitHub Issues in diesem Repo

Dieses Repo enthält mehrere unabhängige Website-Projekte in eigenen Unterverzeichnissen (`ozcorapci.de`, `boxring-wetterau.de`, `buerokollege-ai`, `aralia-ballroom.de`, ...). GitHub Issues sind immer repo-weit — es gibt keine Möglichkeit, sie nach Unterordner zu trennen. Filterung passiert deshalb ausschließlich über **Labels**, nicht über Titel-Präfixe.

## Pflicht-Labels pro Issue

Jedes Issue bekommt genau zwei Labels:

1. **Projekt-Label** — Name des betroffenen Unterverzeichnisses, exakt wie der Ordnername (z. B. `aralia-ballroom.de`, `boxring-wetterau.de`, `buerokollege-ai`)
2. **Prioritäts-Label** — eines von: `priority: high`, `priority: medium`, `priority: low`

Beide Labels werden beim ersten Issue mit diesem Namen automatisch von GitHub angelegt — kein manuelles Vorbereiten nötig.

## Titel

**Kein Website-Präfix mehr im Titel** (früher z. B. `[aralia-ballroom.de] ...` — das ist jetzt redundant, da über das Projekt-Label filterbar).

Empfohlenes Format:
```
[Priorität] Kurze, klare Problembeschreibung
```
Beispiel: `[High] ~227 MB unkomprimierte GIF-Dateien zerstören Ladezeit und Ranking`

## Filtern

Im Issues-Tab links auf das gewünschte Projekt-Label klicken, danach zusätzlich auf das gewünschte Prioritäts-Label — GitHub kombiniert das automatisch als UND-Filter.

Per Link direkt:
```
https://github.com/aozcorap/Webseiten/issues?q=is%3Aissue+is%3Aopen+label%3A%22<projekt>%22+label%3A%22priority%3A+<stufe>%22
```
Beispiel (nur aralia-ballroom.de, High Priority):
```
https://github.com/aozcorap/Webseiten/issues?q=is%3Aissue+is%3Aopen+label%3A%22aralia-ballroom.de%22+label%3A%22priority%3A+high%22
```

## Empfohlener Issue-Body

- **Kategorie:** kurze Einordnung (z. B. Performance, Meta-Daten, Leadgenerierung)
- **Priorität:** entspricht dem Label
- **Kundenverlust-/Umsatz-Einschätzung:** wo im Verhältnis zu anderen offenen Punkten steht dieser bzgl. tatsächlichem Besucher-/Umsatzverlust (nicht nur technische Schwere)
- **Befund:** konkret, verifiziert — kein Vermuten
- **Warum das Ranking/Umsatz kostet:** Business-Impact, verständlich auch für Nicht-Techniker
- **Empfehlung:** konkrete nächste Aktion

## Hinweis zu bestehenden Issues

Ältere Issues (z. B. `[BüroKollege.AI] ...`) nutzen noch das alte Titel-Präfix-Schema ohne Labels. Diese wurden nicht rückwirkend angepasst — die Konvention gilt ab sofort für neue Issues.
