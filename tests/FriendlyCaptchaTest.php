<?php

namespace Ossycodes\FriendlyCaptcha\Tests;

use Orchestra\Testbench\TestCase;
use Ossycodes\FriendlyCaptcha\FriendlyCaptcha;
use Ossycodes\FriendlyCaptcha\FriendlyCaptchaServiceProvider;

class FriendlyCaptchaTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [FriendlyCaptchaServiceProvider::class];
    }

    /**
     * @var FriendlyCaptcha
     */
    private $captcha;

    public function setUp(): void
    {
        parent::setUp();

        $this->captcha = new FriendlyCaptcha('{secret-key}', '{site-key}', 'https://global.frcapi.com/api/v2/captcha/siteverify');
    }

    public function test_it_can_render_widget_scripts_correctly()
    {
        $this->assertTrue($this->captcha instanceof FriendlyCaptcha);

        $expectedScriptOne = '<script type="module" src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.2.0/site.min.js" async defer></script>';
        $expectedScriptTwo = '<script nomodule src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.2.0/site.compat.min.js" async defer></script>';

        $this->assertStringContainsString($expectedScriptOne, $this->captcha->renderWidgetScripts());
        $this->assertStringContainsString($expectedScriptTwo, $this->captcha->renderWidgetScripts());
    }

    public function test_it_can_render_widget_correctly()
    {
        $this->assertTrue($this->captcha instanceof FriendlyCaptcha);

        $expectedWidget = '<div data-sitekey="{site-key}" class="frc-captcha" data-lang="en"></div>';
        $expectedWidgetWithCustomAttributes = '<div data-sitekey="{site-key}" class="frc-captcha dark"></div>';

        $this->assertEquals($expectedWidget, $this->captcha->renderWidget());
        $this->assertEquals($expectedWidgetWithCustomAttributes, $this->captcha->renderWidget(['dark-theme' => true]));
    }
}
