<?php

namespace Ossycodes\FriendlyCaptcha;

use GuzzleHttp\Client;

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
     * error messages
     *
     * @var array
     */
    protected $error = [];

    public $isSuccess = false;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $http;

    public function __construct($secret, $sitekey, $puzzle, $verify, $options = [])
    {
        $this->secret   = $secret;
        $this->sitekey  = $sitekey;
        $this->puzzle  = $puzzle;
        $this->verify  = $verify;
        $this->http     = new Client($options);
    }

    public function renderWidgetScripts($option = 'unpkg')
    {
        if ($option == 'unpkg') {
            return <<<EOF
                <script type="module" src="https://unpkg.com/friendly-challenge@0.9.9/widget.module.min.js" async defer></script>
                <script nomodule src="https://unpkg.com/friendly-challenge@0.9.9/widget.min.js" async defer></script>
              EOF;
        }

        return <<<EOF
                <script type="module" src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.9/widget.module.min.js" async defer></script>
                <script nomodule src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.9/widget.min.js" async defer></script>
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
        $attributes['data-puzzle-endpoint'] = $this->puzzle;

        $attributes['data-sitekey'] = $this->sitekey;

        if (isset($attributes['dark-theme'])) {
            $attributes['class'] = 'frc-captcha dark';
            unset($attributes['dark-theme']);
            return $attributes;
        }

        $attributes['class'] = trim('frc-captcha');

        $locale = \App::currentLocale();
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
     */
    public function verifyRequest($solution)
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
     */
    public function verifyResponse($solution)
    {
        if (empty($solution)) {
            return false;
        }

        $verifyResponse = $this->sendRequestVerify([
            'solution' => $solution,
            'secret'   => $this->secret,
            'sitekey'  => $this->sitekey,
        ]);

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
     * @param array $data
     *
     * @return array
     */
    protected function sendRequestVerify(array $data = [])
    {
        $response = $this->http->request('POST', $this->verify, [
            'form_params' => $data,
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
