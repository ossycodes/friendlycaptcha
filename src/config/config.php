<?php

return [
    'secret'            => env('FRIENDLY_CAPTCHA_SECRET'),
    'sitekey'           => env('FRIENDLY_CAPTCHA_SITEKEY'),
    'options'           => [
        'timeout' => 30,
    ],
];
