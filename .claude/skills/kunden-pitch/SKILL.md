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

Lege pro Entwurf einen `:root`-Block an: Farben, Typo-Skala, Abstands-Skala,
Radien. Danach nur noch Variablen verwenden - kein einziger Hex-Wert und
kein einziger `px`-Wert im Markup.

Farbe leitet sich aus dem Betrieb ab, nicht aus einer Palette von der
Stange. Ein Malerbetrieb darf Farbe zeigen. Ein Schreiner lebt von Holztoenen
und Materialfotos. Ein Dachdecker von Anthrazit und Himmel. Falls der Kunde
ein Logo hat, kommt die Primaerfarbe von dort.

Typografie: ein Paar, nicht drei Schriften. Konkrete Vorschlaege nach
Gewerk in `references/richtungen.md`.

## Phase 5 - Bau

Statisches HTML/CSS/JS, passend zum Rest dieses Repos. Kein Framework.

Umfang: Eine Seite, die ueberzeugt, hat mindestens Hero, Leistungen im
Detail, Referenzen mit Bildern, Ueber-den-Betrieb, Ablauf, Einzugsgebiet,
Kontakt, Footer. Drei Sektionen sehen nach Demo aus, nicht nach Website.

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
