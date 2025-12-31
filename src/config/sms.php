<?php
return [
    'default' => env('SMS_CONNECTION', 'smsir'),
    
    'smsir' => [
        'api-key' => env('SMS_API_KEY'),
        'secret-key' => env('SMS_API_SECRET_KEY'),
        'api-url' => env('SMS_API_URL', 'https://ws.sms.ir/'),
        'line-number' => env('SMS_LINE_NUMBER'),
        'template-id' => env('SMS_TEMPLATE_ID', 424974),
    ],
    
    'rayansms' => [
        'api-key' => env('RAYANSMS_API_KEY'),
        'api-url' => env('RAYANSMS_API_URL', 'https://rayansms.com/api/'),
    ],
    
    'kavenegar' => [
        'api-key' => env('KAVENEGAR_API_KEY'),
        'api-url' => env('KAVENEGAR_API_URL', 'https://api.kavenegar.com/v1/'),
        'number' => env('KAVENEGAR_NUMBER'),
        'verification-template' => env('KAVENEGAR_VERIFICATION_TEMPLATE'),
    ],
];
