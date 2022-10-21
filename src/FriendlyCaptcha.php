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
}
