# Schriften

Referenz-Manifest der acht WOFF2-Dateien (Archivo 500/600/700/800, Source Sans 3
400/500/600/700, lateinischer Subset), die als Base64 in `index.html`,
`impressum.html` und `datenschutz.html` eingebettet sind. Die Binärdateien selbst
liegen nicht im Repo (nur als Data-URI in den HTML-Dateien) — dieses Manifest
dient als Nachweis von Herkunft und Größe, falls die Schriften einmal neu gebaut
werden müssen. Quelle: `fonts.googleapis.com`, per `curl` mit Desktop-User-Agent
abgerufen (nur so liefert Google WOFF2 statt des älteren WOFF-Formats aus).
