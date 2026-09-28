<?php
declare(strict_types=1);

/**
 * Admin-Endpunkt fuer den Shop-Adminbereich (shop/index.html): liefert alle
 * gespeicherten Bestellungen, markiert eine Bestellung als bezahlt, loescht
 * eine Bestellung, oder liefert einen CSV-Export (eine Zeile je bestelltem
 * Artikel) fuer eine eigene Pivot-Auswertung in Excel.
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
    // Zeilenweiser Export (eine Zeile je bestelltem Artikel) fuer eine
    // Pivot-Auswertung in Excel.
    $orders = OrdersStore::all();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="bestellungen-pivot.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF"); // BOM, damit Excel Umlaute korrekt zeigt
    fputcsv($out, ['Bestellnr.', 'Datum', 'Mitglied', 'E-Mail', 'Artikel', 'Größe', 'Farbe', 'Preis', 'Status'], ';');
    foreach ($orders as $order) {
        $status = ($order['status'] ?? '') === 'bezahlt' ? 'Bezahlt' : 'Offen';
        foreach (($order['items'] ?? []) as $item) {
            $price = isset($item['price']) && is_numeric($item['price']) ? number_format((float) $item['price'], 2, ',', '') : '-';
            fputcsv($out, [
                $order['id'] ?? '-',
                $order['createdAt'] ?? '-',
                $order['member'] ?? '-',
                $order['email'] ?? '-',
                $item['name'] ?? '-',
                $item['size'] ?? '-',
                $item['color'] ?? '-',
                $price,
                $status,
            ], ';');
        }
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
