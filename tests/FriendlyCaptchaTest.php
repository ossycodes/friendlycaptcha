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
     * @var FriendlyCaptchaTest
     */
    private $captcha;

    public function setUp(): void
    {
        parent::setUp();

        $this->captcha = new FriendlyCaptcha('{secret-key}', '{site-key}', 'https://api.friendlycaptcha.com/api/v1/siteverify', 'https://api.friendlycaptcha.com/api/v1/siteverify');
    }

    /**
     * @test
     */
    public function it_can_render_unpkg_widget_script_correctly()
    {
        $this->assertTrue($this->captcha instanceof FriendlyCaptcha);

        $expectedScriptOne  = '<script type="module" src="https://unpkg.com/friendly-challenge@0.9.9/widget.module.min.js" async defer></script>';
        $expectedScriptTwo  = '<script nomodule src="https://unpkg.com/friendly-challenge@0.9.9/widget.min.js" async defer></script>';

        $this->assertStringContainsString($expectedScriptOne, $this->captcha->renderWidgetScripts());
        $this->assertStringContainsString($expectedScriptTwo, $this->captcha->renderWidgetScripts());
    }

    /**
     * @test
     */
    public function it_can_render_jsdelivr_widget_script_correctly()
    {
        $this->assertTrue($this->captcha instanceof FriendlyCaptcha);

        $expectedScriptOne = '<script type="module" src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.9/widget.module.min.js" async defer></script>';
        $expectedScriptTwo = '<script nomodule src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.9/widget.min.js" async defer></script>';

        $this->assertStringContainsString($expectedScriptOne, $this->captcha->renderWidgetScripts('jsdelivr'));
        $this->assertStringContainsString($expectedScriptTwo, $this->captcha->renderWidgetScripts('jsdelivr'));
    }

    /**
     * @test
     */
    public function it_can_render_widget_correctly()
    {
        $this->assertTrue($this->captcha instanceof FriendlyCaptcha);

        $expectedWidget = '<div data-puzzle-endpoint="https://api.friendlycaptcha.com/api/v1/siteverify" data-sitekey="{site-key}" class="frc-captcha" data-lang="en"></div>';
        $expectedWidgetWithCustomAttributes = '<div data-puzzle-endpoint="https://api.friendlycaptcha.com/api/v1/siteverify" data-sitekey="{site-key}" class="frc-captcha dark"></div>';

        $this->assertEquals($expectedWidget, $this->captcha->renderWidget());
        $this->assertEquals($expectedWidgetWithCustomAttributes, $this->captcha->renderWidget(['dark-theme' => true]));
    }
}
