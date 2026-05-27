<?php

namespace Ossycodes\FriendlyCaptcha\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Ossycodes\FriendlyCaptcha\FriendlyCaptcha as FriendlyCaptchaClient;

class FriendlyCaptcha implements ValidationRule
{
    protected $friendlyCaptchaClient;

    public function __construct(
        FriendlyCaptchaClient $friendlyCaptcha
    ) {
        $this->friendlyCaptchaClient = $friendlyCaptcha;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = $this->friendlyCaptchaClient->verifyResponse($value);

        if ($response->isSuccess()) {
            return;
        }

        foreach ($response->getErrors() as $errorCode) {
            $fail($this->mapErrorCodeToMessage($errorCode));
        }
    }

    protected function mapErrorCodeToMessage(string $code): string
    {
        switch ($code) {
            case "auth_required":
                return __('friendlycaptcha::validation.auth_required');
            case "auth_invalid":
                return __('friendlycaptcha::validation.auth_invalid');
            case "sitekey_invalid":
                return __('friendlycaptcha::validation.sitekey_invalid');
            case "response_missing":
                return __('friendlycaptcha::validation.response_missing');
            case "response_invalid":
                return __('friendlycaptcha::validation.response_invalid');
            case "response_timeout":
                return __('friendlycaptcha::validation.response_timeout');
            case "response_duplicate":
                return __('friendlycaptcha::validation.response_duplicate');
            case "bad_request":
                return __('friendlycaptcha::validation.bad_request');
            default:
                return __('friendlycaptcha::validation.unexpected');
        }
    }
}
