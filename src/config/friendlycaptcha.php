<?php

return [
    'secret'            => env('FRIENDLY_CAPTCHA_SECRET'),
    'sitekey'           => env('FRIENDLY_CAPTCHA_SITEKEY'),
    'puzzle_endpoint'   => env('FRIENDLY_CAPTCHA_PUZZLE_ENDPOINT', 'https://api.friendlycaptcha.com/api/v1/puzzle'),
    'verify_endpoint'   => env('FRIENDLY_CAPTCHA_VERIFY_ENDPOINT', 'https://api.friendlycaptcha.com/api/v1/siteverify'),
    'options'           => [
        'timeout'       => 30,
        'http_errors'   => false,
    ],
];
