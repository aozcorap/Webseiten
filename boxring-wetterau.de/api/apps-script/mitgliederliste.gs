/**
 * Google Apps Script fuer das Sheet "Boxring Wetterau – Mitgliederliste".
 *
 * Zweck: PHP (api/anmeldung.php) schickt bei jeder Online-Anmeldung einen
 * simplen HTTP-POST hierher, dieses Skript haengt die Zeile an und vergibt
 * dabei die naechste freie Mitgliedsnummer. Kein Google-Cloud-Projekt, keine
 * Service-Account-Datei noetig - das Skript hat als Teil des Sheets automatisch
 * Schreibrechte auf genau dieses Sheet.
 *
 * EINRICHTUNG (einmalig, ca. 3 Minuten):
 * 1. Das Sheet "Boxring Wetterau – Mitgliederliste" in Google Sheets oeffnen.
 * 2. Menü: Erweiterungen -> Apps Script.
 * 3. Den kompletten Inhalt dieser Datei in den Editor einfuegen (vorhandenen
 *    Beispielcode ersetzen).
 * 4. In Zeile ~20 SHARED_SECRET durch ein eigenes, langes Zufallspasswort
 *    ersetzen (z.B. mit einem Passwort-Generator erzeugen). Dasselbe Secret
 *    muss anschliessend in api/config.php unter GOOGLE_SHEETS_WEBAPP_SECRET
 *    eingetragen werden.
 * 5. Oben rechts "Bereitstellen" -> "Neue Bereitstellung".
 *    - Typ: "Web-App"
 *    - Ausfuehren als: "Ich" (dein Google-Konto)
 *    - Zugriff: "Jeder" (das Secret in Schritt 4 schuetzt den Endpunkt)
 * 6. Nach dem Bestaetigen der Berechtigungen (Google fragt, ob das Skript auf
 *    das Sheet zugreifen darf - das ist noetig und erwartet) die angezeigte
 *    Web-App-URL kopieren (endet auf ".../exec").
 * 7. Diese URL in api/config.php unter GOOGLE_SHEETS_WEBAPP_URL eintragen.
 *
 * Bei einer erneuten Aenderung dieses Skripts: erneut "Bereitstellen" ->
 * "Bereitstellungen verwalten" -> Stift-Symbol -> neue Version waehlen ->
 * "Bereitstellen" (die URL bleibt dabei gleich).
 */

var SHARED_SECRET = 'HIER-EIGENES-LANGES-ZUFALLSPASSWORT-EINTRAGEN';
var SHEET_NAME = 'Sheet1';
var MITGLIEDSNR_SPALTE = 7; // Spalte G

function doPost(e) {
  try {
    var payload = JSON.parse(e.postData.contents);

    if (!payload || payload.secret !== SHARED_SECRET) {
      return jsonResponse({ success: false, message: 'unauthorized' });
    }

    var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName(SHEET_NAME);
    if (!sheet) {
      return jsonResponse({ success: false, message: 'Sheet "' + SHEET_NAME + '" nicht gefunden' });
    }

    var row = payload.row;
    if (!Array.isArray(row)) {
      return jsonResponse({ success: false, message: 'row fehlt oder ist kein Array' });
    }

    var neueMitgliedsnr = naechsteMitgliedsnr(sheet);
    row[MITGLIEDSNR_SPALTE - 1] = neueMitgliedsnr;

    sheet.appendRow(row);

    return jsonResponse({ success: true, mitgliedsnr: neueMitgliedsnr });
  } catch (err) {
    return jsonResponse({ success: false, message: String(err) });
  }
}

function naechsteMitgliedsnr(sheet) {
  var lastRow = sheet.getLastRow();
  if (lastRow < 2) {
    return 1;
  }
  var values = sheet.getRange(2, MITGLIEDSNR_SPALTE, lastRow - 1, 1).getValues();
  var max = 0;
  values.forEach(function (r) {
    var v = parseInt(r[0], 10);
    if (!isNaN(v) && v > max) {
      max = v;
    }
  });
  return max + 1;
}

function jsonResponse(obj) {
  return ContentService.createTextOutput(JSON.stringify(obj)).setMimeType(ContentService.MimeType.JSON);
}
