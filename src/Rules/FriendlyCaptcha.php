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
                return "You forgot to add the secret (=API key) parameter.";
                break;
            case "secret_invalid":
                return "The API key you provided was invalid.";
                break;
            case "solution_missing":
                return "You forgot to add the solution parameter.";
                break;
            case "bad_request":
                return "Something else is wrong with your request, e.g. your request body is empty.";
                break;
            case "solution_invalid":
                return "The solution you provided was invalid (perhaps the user tried to tamper with the puzzle).";
                break;
            case "solution_timeout_or_duplicate":
                return "The puzzle that the solution was for has expired or has already been used.";
                break;
            default:
                return  "An unexpected error occurred.";
        }
    }
}
