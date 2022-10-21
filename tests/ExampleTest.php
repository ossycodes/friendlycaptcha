<?php

namespace Ossycodes\FriendlyCaptcha\Tests;

use Orchestra\Testbench\TestCase;
use Ossycodes\FriendlyCaptcha\FriendlyCaptchaServiceProvider;

class ExampleTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [FriendlyCaptchaServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
