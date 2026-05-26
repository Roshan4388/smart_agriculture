<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Authentication required to save the plan.']);
    exit;
}

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid request data.']);
    exit;
}

$signatureName = trim($data['signature_name'] ?? '');
$notes = trim($data['notes'] ?? '');
$selectedCrops = $data['selected_crops'] ?? [];
$planLat = isset($data['plan_lat']) ? floatval($data['plan_lat']) : null;
$planLng = isset($data['plan_lng']) ? floatval($data['plan_lng']) : null;
$planArea = isset($data['plan_area_meters']) ? floatval($data['plan_area_meters']) : 0;

if ($signatureName === '') {
    echo json_encode(['success' => false, 'error' => 'Please provide a signature name.']);
    exit;
}

if (!is_array($selectedCrops) || empty($selectedCrops)) {
    echo json_encode(['success' => false, 'error' => 'Select at least one crop before saving.']);
    exit;
}

if (!is_numeric($planLat) || !is_numeric($planLng)) {
    echo json_encode(['success' => false, 'error' => 'Invalid plan coordinates.']);
    exit;
}

$record = [
    'user_id' => $_SESSION['user_id'],
    'signature_name' => $signatureName,
    'selected_crops' => array_values($selectedCrops),
    'plan_lat' => $planLat,
    'plan_lng' => $planLng,
    'plan_area_meters' => $planArea,
    'notes' => $notes,
    'created_at' => date('Y-m-d H:i:s'),
];

$record['plan_signature'] = hash('sha256', $signatureName . json_encode($record['selected_crops']) . $planLat . $planLng . $planArea . $notes . $record['created_at']);

$storageDir = __DIR__ . '/../data';
$storagePath = $storageDir . '/signed_plans.json';

if (!is_dir($storageDir)) {
    if (!mkdir($storageDir, 0755, true) && !is_dir($storageDir)) {
        echo json_encode(['success' => false, 'error' => 'Unable to create storage directory.']);
        exit;
    }
}

$existingPlans = [];
if (file_exists($storagePath)) {
    $content = file_get_contents($storagePath);
    $existingPlans = json_decode($content, true) ?: [];
}

$existingPlans[] = $record;
if (file_put_contents($storagePath, json_encode($existingPlans, JSON_PRETTY_PRINT)) === false) {
    echo json_encode(['success' => false, 'error' => 'Unable to save the signed plan.']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Plan saved successfully.', 'plan_signature' => $record['plan_signature']]);
