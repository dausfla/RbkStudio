<?php
/**
 * API Endpoint to record WhatsApp Lead Enquiries into DB
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    $input = $_POST;
}

$db = getDB();
$stmt = $db->prepare("INSERT INTO leads 
    (name, wa_number, location, land_area, building_area, floors, building_type, style, budget, full_message, source_cta) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$success = $stmt->execute([
    $input['name'] ?? null,
    $input['wa_number'] ?? null,
    $input['location'] ?? null,
    $input['land_area'] ?? null,
    $input['building_area'] ?? null,
    $input['floors'] ?? null,
    $input['building_type'] ?? null,
    $input['style'] ?? null,
    $input['budget'] ?? null,
    $input['full_message'] ?? null,
    $input['source_cta'] ?? 'UMUM'
]);

echo json_encode(['success' => (bool)$success]);
