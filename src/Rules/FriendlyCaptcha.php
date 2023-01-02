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
            case "secret_missing":
                return __('validation.secret_missing');
                break;
            case "secret_invalid":
                return __('validation.secret_invalid');
                break;
            case "solution_missing":
                return __('validation.solution_missing');
                break;
            case "bad_request":
                return __('validation.bad_request');
                break;
            case "solution_invalid":
                return __('validation.solution_invalid');
                break;
            case "solution_timeout_or_duplicate":
                return __('validation.solution_timeout_or_duplicate');
                break;
            default:
                return  __('validation.unexpected');
        }
    }
}
