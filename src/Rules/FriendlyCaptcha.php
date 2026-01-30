<?php

namespace Ossycodes\FriendlyCaptcha\Rules;

use Illuminate\Contracts\Validation\Rule;
use Ossycodes\FriendlyCaptcha\FriendlyCaptcha as FriendlyCaptchaClient;

class FriendlyCaptcha implements Rule
{
    protected $friendlyCaptchaClient;

    protected array $messages = [];

    public function __construct(
        FriendlyCaptchaClient $friendlyCaptcha
    ) {
        $this->friendlyCaptchaClient = $friendlyCaptcha;
    }

    public function passes($attribute, $value)
    {
        $response = $this->friendlyCaptchaClient->verifyResponse($value);

        if ($response->isSuccess()) {
            return true;
        }

        foreach ($response->getErrors() as $errorCode) {
            $this->messages[] = $this->mapErrorCodeToMessage($errorCode);
        }

        return false;
    }

    public function message()
    {
        return $this->messages;
    }

    /**
     * map FriendlyCaptcha error code to human readable validation message
     *
     * @var string $code
     */
    protected function mapErrorCodeToMessage(string $code): string
    {
        switch ($code) {
            case "auth_required":
                return __('validation.auth_required');
            case "auth_invalid":
                return __('validation.auth_invalid');
            case "sitekey_invalid":
                return __('validation.sitekey_invalid');
            case "response_missing":
                return __('validation.response_missing');
            case "response_invalid":
                return __('validation.response_invalid');
            case "response_timeout":
                return __('validation.response_timeout');
            case "response_duplicate":
                return __('validation.response_duplicate');
            default:
                return  __('validation.unexpected');
        }
    }
}
