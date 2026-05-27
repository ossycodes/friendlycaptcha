<?php

namespace Ossycodes\FriendlyCaptcha;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FriendlyCaptcha
{
    /**
     * FriendlyCaptcha secret
     *
     * @var string
     */
    protected $secret;

    /**
     * FriendlyCaptcha sitekey
     *
     * @var string
     */
    protected $sitekey;

    /**
     * FriendlyCaptcha verify endpoint
     */
    protected $verify;

    /**
     * error messages
     *
     * @var array
     */
    protected $errors = [];

    public $isSuccess = false;

    /**
     * @var Client
     */
    protected $http;

    public function __construct($secret, $sitekey, $verify, $options = [])
    {
        $this->secret   = $secret;
        $this->sitekey  = $sitekey;
        $this->verify   = $verify;
        $this->http     = new Client($options);
    }

    public function renderWidgetScripts(): string
    {
        return <<<EOF
                <script type="module" src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.2.0/site.min.js" async defer></script>
                <script nomodule src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.2.0/site.compat.min.js" async defer></script>
            EOF;
    }

    public function renderWidget($attributes = [])
    {
        $attributes = $this->prepareAttributes($attributes);
        return '<div' . $this->buildAttributes($attributes) . '></div>';
    }

    /**
     * Prepare HTML attributes and ensure that the correct classes and attributes for captcha are inserted.
     *
     * @param array $attributes
     *
     * @return array
     */
    protected function prepareAttributes(array $attributes)
    {
        $attributes['data-sitekey'] = $this->sitekey;

        if (isset($attributes['dark-theme'])) {
            $attributes['class'] = 'frc-captcha dark';
            unset($attributes['dark-theme']);
            return $attributes;
        }

        $attributes['class'] = trim('frc-captcha');

        $locale = app()->getLocale();

        if (in_array($locale, ["en", "fr", "de", "it", "nl", "pt", "es", "ca", "da", "ja", "ru", "sv", "el", "uk", "bg", "cs", "sk", "no", "fi", "lt", "lt", "pl", "et", "hr", "sr", "sl", "hu", "ro", "zh", "zh_TW", "vi"])) {
            //use supported locale - https://docs.friendlycaptcha.com/#/widget_api?id=data-lang-attribute
            $attributes['data-lang'] = $locale;
        }

        return $attributes;
    }

    /**
     * Build HTML attributes.
     *
     * @param array $attributes
     *
     * @return string
     */
    protected function buildAttributes(array $attributes)
    {
        $html = [];

        foreach ($attributes as $key => $value) {
            $html[] = $key . '="' . $value . '"';
        }

        return count($html) ? ' ' . implode(' ', $html) : '';
    }

    /**
     * Verify FriendlyCaptcha response.
     *
     * @param string $solution
     *
     * @return bool
     * @throws GuzzleException
     */
    public function verifyRequest(string $solution)
    {
        return $this->verifyResponse(
            $solution,
        );
    }

    /**
     * Verify FriendlyCaptcha response.
     *
     * @param string $solution
     *
     * @return self
     * @throws GuzzleException
     */
    public function verifyResponse(string $solution): self
    {
        if (empty($solution)) {
            $this->isSuccess = false;
            $this->errors = [];
            return $this;
        }

        $verifyResponse = $this->sendRequestVerify(
            ['X-API-Key' => $this->secret],
            [
                'response' => $solution,
                'sitekey'  => $this->sitekey,
            ]
        );

        if (isset($verifyResponse['success']) && $verifyResponse['success'] === true) {
            $this->isSuccess = true;
            return $this;
        }

        if (isset($verifyResponse['errors'])) {
            $this->errors  = $verifyResponse['errors'];
        }

        if (isset($verifyResponse['error'])) {
            $this->errors  = [$verifyResponse['error']];
        }

        $this->isSuccess = false;

        return $this;

    }

    /**
     * Send verify request.
     *
     * @param array $headers
     * @param array $data
     * @return array
     * @throws GuzzleException
     */
    protected function sendRequestVerify(array $headers = [], array $data = []): array
    {
        $response = $this->http->request('POST', $this->verify, [
            'headers' => $headers,
            'json' => $data,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function isSuccess()
    {
        return $this->isSuccess;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
