<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OTP configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the default OTP parameters for your application.
    | You can choose the OTP length, expiry time, and SMS provider settings.
    |
    */

    'otp_length' => 4, // Length of the OTP
    'otp_expiry' => 5, // OTP expiry in minutes
    'sms_provider' => 'twilio', // 'log' for local/testing, 'twilio' for production
    'rate_limit' => 1, // OTP send rate limit in minutes

    /*
    |--------------------------------------------------------------------------
    | Twilio configuration (for when sms_provider is 'twilio')
    |--------------------------------------------------------------------------
    */
    'twilio' => [
        'sid' => env('TWILIO_SID', ''),
        'token' => env('TWILIO_TOKEN', ''),
        'from' => env('TWILIO_FROM', ''),
    ],
];
