# Webseiten

Sammlung statischer Kundenwebsites. Reines HTML/CSS/JS, kein Framework,
kein Build-Schritt. Projektuebersicht: siehe README.md.

## Kundenprojekte

Steht ein Redesign oder ein Designvorschlag fuer einen Kunden an, laeuft das
ueber den Skill `kunden-pitch` (`.claude/skills/kunden-pitch/`). Der erzwingt
Bestandsaufnahme, echte Inhalte, drei Entwuerfe und einen Qualitaets-Review.
Nicht daran vorbeiarbeiten - die Abkuerzung ist genau der Grund, warum
Entwuerfe generisch werden.

## Verbindliche Regeln

**Keine erfundenen Inhalte.** Texte, Referenzen, Bewertungen und Zahlen
stammen vom Kunden oder von seiner bestehenden Seite. Fehlt etwas, wird es
als `[TODO: Kunde liefert]` sichtbar markiert und nachgefragt. Nie
ausgedacht - erfundene Angaben fliegen im Kundengespraech auf und kosten
Glaubwuerdigkeit.

**Design-Token zuerst.** Vor dem Markup ein `:root`-Block mit Farben,
Typo-Skala, Abstands-Skala und Radien. Danach ausschliesslich Variablen -
keine Hex-Werte, keine losen Pixelwerte im Markup.

**Hoechstens zwei Schriften** pro Projekt, ueber Google Fonts eingebunden.

**Fotos statt Icons.** Icon-Kacheln als Ersatz fuer Bildmaterial sind das
deutlichste Merkmal generisch wirkender Seiten. Bei Handwerksbetrieben sind
Projektfotos der eigentliche Verkaufsinhalt.

**Mobil zuerst.** Der ueberwiegende Teil der Besucher kommt vom Handy.
Telefonnummer sichtbar im Header und als `tel:`-Link. Bei 360px Breite darf
nichts horizontal scrollen.

**Barrierefreiheit ist Pflicht, nicht Kuer.** Kontrast mindestens 4.5:1 im
Fliesstext, sichtbarer Fokus auf allen bedienbaren Elementen, sinnvolle
Alt-Texte, vollstaendige Tastaturbedienung, `prefers-reduced-motion`
respektieren.

**Jede Seite braucht Impressum und Datenschutz**, bevor sie live geht.

**Ansehen vor Abliefern.** Gerenderte Seite pruefen, nicht nur den Code.
Ungesehene Seiten gelten als ungeprueft.

## Handschrift vermeiden

Jeder Kunde bekommt ein eigenes Erscheinungsbild. Wenn ein neuer Entwurf
aussieht wie das letzte Projekt - dunkler Hero, Farbverlauf, gleiche
Akzentfarbe - ist er nicht fertig. Der Betrieb bestimmt den Look, nicht die
Gewohnheit.

## Sicherheit

Zugangsdaten, API-Schluessel und personenbezogene Laufzeitdaten gehoeren nie
ins Repo. Bestehende Ausnahmen stehen in `.gitignore` und bleiben dort.
