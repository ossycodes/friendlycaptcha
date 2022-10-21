<?php

namespace Ossycodes\FriendlyCaptcha\Facades;

use Illuminate\Support\Facades\Facade;

class FriendlyCaptcha extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ':package_name';
    }
}
