<?php

namespace Ossycodes\FriendlyCaptcha;

use GuzzleHttp\Client;

class FriendlyCaptcha
{
    const VERIFY_API =  'https://api.friendlycaptcha.com/api/v1/siteverify';

    /**
     * The FriendlyCaptcha secret
     *
     * @var string
     */
    protected $secret;

    /**
     * The FriendlyCaptch sitekey
     *
     * @var string
     */
    protected $sitekey;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $http;

    public function __construct($secret, $sitekey, $options = [])
    {
        $this->secret   = $secret;
        $this->sitekey  = $sitekey;
        $this->http     = new Client($options);
    }

    public function renderWidgetScripts($option = 'unpkg')
    {
        if ($option == 'unpkg') {
            return <<<EOF
                <script type="module" src="https://unpkg.com/friendly-challenge@0.9.8/widget.module.min.js" async defer></script>
                <script nomodule src="https://unpkg.com/friendly-challenge@0.9.8/widget.min.js" async defer></script>
              EOF;
        }

        return <<<EOF
                <script type="module" src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.8/widget.module.min.js" async defer></script>
                <script nomodule src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.8/widget.min.js" async defer></script>
            EOF;
    }
}
