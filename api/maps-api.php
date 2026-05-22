<?php
header('Content-Type: application/json');

$type = $_GET['type'] ?? 'boundary';

$payload = [
    'boundary' => [
        'center' => ['lat' => 27.7052, 'lng' => 85.3270],
        'coordinates' => [
            ['lat' => 27.7080, 'lng' => 85.3255],
            ['lat' => 27.7060, 'lng' => 85.3310],
            ['lat' => 27.7035, 'lng' => 85.3302],
            ['lat' => 27.7040, 'lng' => 85.3260],
        ],
    ],
    'tracking' => [
        ['time' => '08:10', 'status' => 'OK', 'message' => 'No boundary movement.'],
        ['time' => '12:15', 'status' => 'Alert', 'message' => 'Neighbor device detected near east fence.'],
        ['time' => '15:40', 'status' => 'OK', 'message' => 'Drone scan complete.'],
    ],
];

if ($type === 'tracking') {
    echo json_encode($payload['tracking']);
    exit;
}

echo json_encode($payload['boundary']);
exit;
