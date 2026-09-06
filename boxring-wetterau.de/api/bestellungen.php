<?php
declare(strict_types=1);

/**
 * Admin-Endpunkt fuer den Shop-Adminbereich (shop/index.html): liefert alle
 * gespeicherten Bestellungen, markiert eine Bestellung als bezahlt, loescht
 * eine Bestellung, oder liefert einen CSV-Export fuer den Reseller (eleven
 * teamsports) - aggregiert nach Artikel/Groesse/Farbe/Logo, da der Reseller
 * nur die Gesamtstueckzahl je Variante braucht, keine Kundennamen.
 *
 * Zugriff nur mit gueltiger ShopAdminSession (siehe shop-login.php) - hier
 * liegen echte Kunden-E-Mail-Adressen, das darf nicht ueber den Quelltext
 * einsehbar oder ohne Login abrufbar sein.
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__ . '/lib/ShopAdminSession.php';
require_once __DIR__ . '/lib/OrdersStore.php';

function respondJson(int $httpCode, array $payload): void
{
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($httpCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

ShopAdminSession::start();
if (!ShopAdminSession::isLoggedIn()) {
    respondJson(401, ['success' => false, 'message' => 'Nicht angemeldet.']);
}

$method = $_SERVER['REQUEST_METHOD'] ?? '';
$action = $_GET['action'] ?? '';

if ($method === 'GET' && $action === 'export') {
    $orders = OrdersStore::all();

    // Aggregation: Artikel + Groesse + Farbe + Logo -> Anzahl. Nur offene
    // (noch nicht bezahlte) und bezahlte Bestellungen gleichermassen
    // beruecksichtigt - der Reseller braucht die volle Stueckzahl, unabhaengig
    // vom Zahlungsstatus.
    $groups = [];
    foreach ($orders as $order) {
        foreach (($order['items'] ?? []) as $item) {
            $key = implode('|', [$item['name'] ?? '-', $item['size'] ?? '-', $item['color'] ?? '-', !empty($item['hasLogo']) ? '1' : '0']);
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'artikel' => $item['name'] ?? '-',
                    'groesse' => $item['size'] ?? '-',
                    'farbe' => $item['color'] ?? '-',
                    'logo' => !empty($item['hasLogo']) ? 'Ja' : 'Nein',
                    'anzahl' => 0,
                ];
            }
            $groups[$key]['anzahl']++;
        }
    }

    $sizeOrder = ['XS' => 0, 'S' => 1, 'M' => 2, 'L' => 3, 'XL' => 4, 'XXL' => 5, '116' => 6, '128' => 7, '140' => 8, '152' => 9, '164' => 10, '176' => 11];
    $rows = array_values($groups);
    usort($rows, function (array $a, array $b) use ($sizeOrder) {
        return [
            $a['artikel'], $sizeOrder[$a['groesse']] ?? 99, $a['farbe'], $a['logo'],
        ] <=> [
            $b['artikel'], $sizeOrder[$b['groesse']] ?? 99, $b['farbe'], $b['logo'],
        ];
    });

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="bestellungen-eleven-teamsports.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF"); // BOM, damit Excel Umlaute korrekt zeigt
    fputcsv($out, ['Artikel', 'Größe', 'Farbe', 'Logo', 'Anzahl'], ';');
    foreach ($rows as $row) {
        fputcsv($out, [$row['artikel'], $row['groesse'], $row['farbe'], $row['logo'], $row['anzahl']], ';');
    }
    fclose($out);
    exit;
}

if ($method === 'GET') {
    respondJson(200, ['success' => true, 'orders' => OrdersStore::all()]);
}

if ($method === 'POST') {
    $input = json_decode((string) file_get_contents('php://input'), true);
    $id = is_array($input) && isset($input['id']) && is_string($input['id']) ? $input['id'] : null;

    if ($id === null) {
        respondJson(400, ['success' => false, 'message' => 'Bestellnummer fehlt.']);
    }

    if ($action === 'markPaid') {
        $paidVia = is_array($input) && isset($input['paidVia']) && is_string($input['paidVia']) && in_array($input['paidVia'], ['Überweisung', 'Bar im Training'], true)
            ? $input['paidVia']
            : null;
        if ($paidVia === null) {
            respondJson(400, ['success' => false, 'message' => 'Zahlungsart fehlt oder ungueltig.']);
        }
        $found = OrdersStore::markPaid($id, $paidVia);
        if (!$found) {
            respondJson(404, ['success' => false, 'message' => 'Bestellung nicht gefunden.']);
        }
        respondJson(200, ['success' => true]);
    }

    if ($action === 'markOpen') {
        $found = OrdersStore::markOpen($id);
        if (!$found) {
            respondJson(404, ['success' => false, 'message' => 'Bestellung nicht gefunden.']);
        }
        respondJson(200, ['success' => true]);
    }

    if ($action === 'delete') {
        $found = OrdersStore::delete($id);
        if (!$found) {
            respondJson(404, ['success' => false, 'message' => 'Bestellung nicht gefunden.']);
        }
        respondJson(200, ['success' => true]);
    }

    respondJson(400, ['success' => false, 'message' => 'Unbekannte Aktion.']);
}

respondJson(405, ['success' => false, 'message' => 'Methode nicht erlaubt.']);
