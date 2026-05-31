<?php
header('Content-Type: application/json');

$type = $_GET['type'] ?? 'current';

$data = [
    'current' => [
        'temperature' => 25,
        'humidity' => 65,
        'wind_speed' => '10 km/h',
        'condition' => 'Light rain',
        'recommendation' => 'Keep irrigation low and protect new seedlings from strong showers.',
    ],
    'forecast' => [
        ['day' => 'Monday', 'high' => 25, 'low' => 19, 'condition' => 'Rain'],
        ['day' => 'Tuesday', 'high' => 26, 'low' => 20, 'condition' => 'Cloudy'],
        ['day' => 'Wednesday', 'high' => 24, 'low' => 18, 'condition' => 'Showers'],
        ['day' => 'Thursday', 'high' => 27, 'low' => 20, 'condition' => 'Clear'],
        ['day' => 'Friday', 'high' => 28, 'low' => 21, 'condition' => 'Sunny'],
    ],
];

if ($type === 'forecast') {
    echo json_encode($data['forecast']);
    exit;
}

echo json_encode($data['current']);
exit;
