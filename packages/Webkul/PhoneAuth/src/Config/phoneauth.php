<?php

return [
    'otp_length' => 4, // Length of the OTP
    'otp_expiry' => 5, // OTP expiry in minutes
    'sms_provider' => 'twilio', // or 'log' for local/testing
    'rate_limit' => 1, // OTP send rate limit in minutes
];
