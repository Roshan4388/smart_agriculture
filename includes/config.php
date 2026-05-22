<?php
// Application configuration settings
date_default_timezone_set('Asia/Kathmandu');
return [
    'app_name' => 'Smart Agriculture',
    'version' => '1.0.0',
    'base_url' => '/smart_agriculture',
    'supported_languages' => ['en' => 'English', 'ne' => 'नेपाली'],
    'sms' => [
        'provider' => 'log',
        'test_mode' => true,
        'twilio' => [
            'sid' => '',
            'token' => '',
            'from' => '',
        ],
    ],
    'otp' => [
        'length' => 6,
        'expires_minutes' => 10,
    ],
];
?>
