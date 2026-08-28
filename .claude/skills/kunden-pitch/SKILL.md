---
name: kunden-pitch
description: Redesign-Entwurf oder neue Website fuer einen Kunden bauen - von der alten Seite bis zum praesentationsreifen Mockup. Nutze diesen Skill, sobald ein Kundenprojekt ansteht: "Redesign fuer X", "Designvorschlag fuer einen Schreiner", "mach aus der alten Seite was Gutes", "Mockup fuer einen Handwerksbetrieb". Erzwingt Bestandsaufnahme, echte Inhalte und einen Qualitaets-Review, damit der Entwurf nach Agentur aussieht und nicht nach Platzhalter-Demo.
---

# Kunden-Pitch

Ziel ist nicht "eine Website". Ziel ist ein Entwurf, der im Kundengespraech
den Auftrag holt. Das sind zwei verschiedene Dinge.

Ein Entwurf gewinnt, wenn der Kunde sich selbst darin wiedererkennt - seine
Arbeit, seine Kunden, seine Worte. Er verliert, wenn er nach Vorlage aussieht,
in die jemand seinen Namen eingesetzt hat.

Arbeite die Phasen der Reihe nach ab. Phase 1 ist nicht optional: ohne
Bestandsaufnahme entsteht generisches Zeug, und genau das ist das Problem,
das dieser Skill loest.

## Umfang klaeren

Zwei Faelle, unterschiedlich viel Arbeit:

**Voller Pitch** - der Kunde soll ueberzeugt werden. Alle Phasen, drei
Entwurfsrichtungen. Das ist der Normalfall bei einer echten Anfrage.

**Schneller Einzelentwurf** - du willst nur sehen, wie es aussehen koennte.
Phase 3 auf eine Richtung reduzieren, alles andere bleibt. Besonders Phase 1
und Phase 6 nicht ueberspringen: ohne Bestandsaufnahme wird es generisch,
ohne Review bleiben die Fehler drin.

Im Zweifel nachfragen, welcher der beiden Faelle gemeint ist.

## Dateien in diesem Skill

| Datei | Wofuer |
|---|---|
| `references/basis.css` | Token und Grundgestaltung. Kopieren, vier Stellen anpassen. |
| `references/muster.md` | Fertiges Markup je Sektion. Zusammensetzen statt erfinden. |
| `references/richtungen.md` | Die drei Entwurfsrichtungen, Farbe je Gewerk, Schriftpaare. |
| `references/handwerk.md` | Was bei Handwerkskunden Anfragen erzeugt. |
| `references/qualitaet.md` | Kill-Kriterien fuer den Review vor der Praesentation. |

## Phase 1 - Bestandsaufnahme

Bevor irgendetwas gebaut wird, die alte Seite des Kunden auslesen. Ohne
diesen Schritt gibt es keinen Entwurf.

Hol dir mit WebFetch oder curl von der Live-Seite:

- **Alle Leistungen**, wortwoertlich wie der Kunde sie nennt. Ein Maler
  schreibt "Tapezierarbeiten", nicht "Wandgestaltungsloesungen". Die Sprache
  des Kunden ist das Wertvollste an der alten Seite.
- **Firmendaten**: Inhaber, Gruendungsjahr, Adresse, Telefon, E-Mail,
  Oeffnungszeiten, Einzugsgebiet.
- **Vertrauenssignale**: Meisterbetrieb, Innungsmitgliedschaft, Zertifikate,
  Jahre am Markt, Mitarbeiterzahl, Auszeichnungen.
- **Referenzen und Bewertungen**, inkl. Google-Bewertungen falls vorhanden.
- **Alle Bilder.** Lade sie herunter. Eigene Projektfotos des Kunden schlagen
  jedes Stockfoto - auch wenn sie technisch schlechter sind.

Halte das Ergebnis in `<projekt>/BESTANDSAUFNAHME.md` fest. Das ist die
Faktenbasis; ab hier wird nichts mehr erfunden.

Fehlt etwas, das der Entwurf braucht: als offene Frage in die Datei und den
Nutzer fragen. Nicht ausdenken.

## Phase 2 - Brief

Aus der Bestandsaufnahme drei Dinge festlegen und aufschreiben:

**Wer entscheidet?** Bei Handwerk selten die Person, die googelt. Oft der
Ehepartner, der Hausverwalter, der Bautraeger. Der Entwurf muss die
ueberzeugen.

**Was soll passieren?** Anruf, Formular, WhatsApp, Vor-Ort-Termin. Bei
lokalem Handwerk fast immer der Anruf. Das bestimmt das gesamte Layout.

**Was ist der eine Grund, diesen Betrieb zu nehmen?** Nicht drei. Einer.
"Seit 1987 in Familienhand", "in 48 Stunden vor Ort", "Meisterbetrieb mit
eigener Werkstatt". Dieser Satz traegt den Hero.

## Phase 3 - Drei Richtungen

Immer drei Entwuerfe, nie einen. Das veraendert das Kundengespraech
grundlegend: aus "gefaellt mir / gefaellt mir nicht" wird "welcher davon".
Der Kunde waehlt aus statt zu urteilen.

Die drei muessen sich deutlich unterscheiden - nicht dieselbe Seite in drei
Farben. Unterschiedliche Typografie, unterschiedliche Bildsprache,
unterschiedlicher Aufbau.

Bewaehrtes Trio fuer Handwerk und lokale Dienstleister:

1. **Bodenstaendig** - hell, warm, foto-getragen, grosse Schrift, ruhig.
   Fuer Betriebe, deren Staerke Vertrauen und Bestand ist.
2. **Handwerklich-praezise** - viel Weissraum, strenge Typografie,
   Detailaufnahmen von Material und Arbeit. Fuer Betriebe, die Qualitaet
   und Praezision verkaufen.
3. **Kraeftig** - satte Farbe, hoher Kontrast, klare Kante. Fuer Betriebe,
   die sich sichtbar vom Wettbewerb absetzen wollen.

Details und Farbwelten: `references/richtungen.md`.

Wichtig: Deine eigene Handschrift ist hier fehl am Platz. Wenn der Entwurf
aussieht wie das letzte Projekt, ist er falsch. Der Betrieb bestimmt den
Look, nicht die Gewohnheit.

## Phase 4 - Design-System

Erst Token, dann Bau. Nie umgekehrt.

Kopiere `references/basis.css` in das Projekt. Darin stecken bereits eine
abgestimmte Typo-Skala, eine Abstands-Skala, Fokus-Zustaende, einheitliche
Bildformate, die mobile Anrufleiste und `prefers-reduced-motion`.

Anzupassen sind genau vier Stellen, alle im `:root`-Block markiert: Farben,
Schriftpaar, Radius und Seitenbreite. Alles andere bleibt unveraendert - die
Skalen sind aufeinander abgestimmt, und einzeln veraenderte Werte zerstoeren
genau den Rhythmus, der eine Seite professionell wirken laesst.

Danach ausschliesslich Variablen verwenden: kein Hex-Wert und kein loser
Pixelwert im Markup.

Farbe leitet sich aus dem Betrieb ab, nicht aus einer Palette von der
Stange. Ein Malerbetrieb darf Farbe zeigen. Ein Schreiner lebt von
Holztoenen. Ein Dachdecker von Anthrazit und Himmel. Hat der Kunde ein Logo,
kommt die Primaerfarbe von dort. Anhaltspunkte je Gewerk und die
Schriftpaare: `references/richtungen.md`.

## Phase 5 - Bau

Statisches HTML/CSS/JS, passend zum Rest dieses Repos. Kein Framework.

Setze die Seite aus den Bausteinen in `references/muster.md` zusammen, statt
Sektionen neu zu erfinden. Dort steht fuer jeden Abschnitt fertiges Markup -
Hero, Vertrauensleiste, Leistungen, Referenzen, Ablauf, Kontakt, Anrufleiste,
Footer. Das ist der Unterschied zwischen einem brauchbaren ersten Wurf und
fuenf Korrekturschleifen.

Besondere Aufmerksamkeit gilt den Leistungen. Der Standardreflex sind drei
gleich grosse Karten mit Piktogramm - genau das laesst eine Seite generiert
aussehen. `references/muster.md` zeigt die Alternative: jede Leistung als
eigener Block mit echtem Foto, Seite wechselnd.

Umfang: mindestens Hero, Vertrauensleiste, Leistungen, Referenzen, Ablauf,
Betrieb, Einzugsgebiet, Kontakt, Footer. Drei Sektionen sehen nach Demo aus,
nicht nach Website.

Inhalte kommen ausschliesslich aus `BESTANDSAUFNAHME.md`. Kein Lorem ipsum,
keine erfundenen Referenzen, keine erfundenen Bewertungen. Fehlt echter
Inhalt, wird die Luecke sichtbar als `[TODO: Kunde liefert]` markiert - eine
ehrliche Luecke ist im Gespraech besser als ein erfundener Satz, den der
Kunde als falsch erkennt.

Gewerkespezifische Muster, die tatsaechlich Anfragen erzeugen:
`references/handwerk.md`.

## Phase 6 - Review

Der Schritt, der den Unterschied macht. Nie ueberspringen.

Rendere die Seite und sieh sie dir an. Mit Playwright, sonst per Screenshot.
Ungesehene Seiten sind ungeprueft.

Gehe dann `references/qualitaet.md` durch. Dort stehen die Kill-Kriterien -
die Merkmale, an denen ein Entwurf sofort als hingerotzt erkennbar ist.
Jeder Treffer wird behoben, bevor der Kunde das sieht.

Mindestens zwei Durchlaeufe. Der erste findet die groben Fehler, der zweite
die, die den professionellen Eindruck ausmachen.
