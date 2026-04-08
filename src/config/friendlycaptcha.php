<?php

return [
    'secret'            => env('FRIENDLY_CAPTCHA_SECRET'),
    'sitekey'           => env('FRIENDLY_CAPTCHA_SITEKEY'),
    'verify_endpoint'   => env('FRIENDLY_CAPTCHA_VERIFY_ENDPOINT', 'https://global.frcapi.com/api/v2/captcha/siteverify'), //see https://developer.friendlycaptcha.com/docs/v2/guides/upgrading-from-v1/backend-integration
    'options'           => [
        'timeout'       => 30,
        'http_errors'   => false,
    ],
];
