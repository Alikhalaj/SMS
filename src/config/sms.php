<?php
return [
    'default' =>env('SMS_CONNECTION'),
    'smsir' => [
        'api-key' => env('SMS_API_KEY'),
        'secret-key' => env('SMS_API_SECRET_KEY'),
        'api-url' => 'https://ws.sms.ir/',
    ],
    'rayansms'=>[
        'api-key'=>env('SMS_API_KEY')
    ],
    'kavenegar'=>[
        'api-key'=>env('SMS_API_KEY'),
        'api-url' => env('SMS_API_URL','https://api.kavenegar.com/v1/'),
    ],
    'test'=>'sdfsdf',
];
