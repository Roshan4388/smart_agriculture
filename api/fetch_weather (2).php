<?php
header('Content-Type: application/json');

// Mock weather data - Replace with actual API integration
$weatherData = [
    'current' => [
        'temperature' => 24,
        'humidity' => 65,
        'condition' => 'Clear',
        'wind_speed' => 5,
        'pressure' => 1013,
        'uv_index' => 6
    ],
    'hourly' => [
        [
            'time' => date('H:00', strtotime('+0 hours')),
            'temperature' => 24,
            'humidity' => 65,
            'condition' => 'Clear',
            'wind_speed' => 5
        ],
        [
            'time' => date('H:00', strtotime('+1 hours')),
            'temperature' => 25,
            'humidity' => 62,
            'condition' => 'Clear',
            'wind_speed' => 6
        ],
        [
            'time' => date('H:00', strtotime('+2 hours')),
            'temperature' => 26,
            'humidity' => 60,
            'condition' => 'Sunny',
            'wind_speed' => 7
        ],
        [
            'time' => date('H:00', strtotime('+3 hours')),
            'temperature' => 27,
            'humidity' => 58,
            'condition' => 'Sunny',
            'wind_speed' => 8
        ],
        [
            'time' => date('H:00', strtotime('+4 hours')),
            'temperature' => 26,
            'humidity' => 60,
            'condition' => 'Partly Cloudy',
            'wind_speed' => 6
        ],
        [
            'time' => date('H:00', strtotime('+5 hours')),
            'temperature' => 25,
            'humidity' => 62,
            'condition' => 'Cloudy',
            'wind_speed' => 5
        ]
    ]
];

echo json_encode($weatherData);
?>
