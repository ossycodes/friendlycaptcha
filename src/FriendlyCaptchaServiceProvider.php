<?php

namespace Ossycodes\FriendlyCaptcha;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Ossycodes\FriendlyCaptcha\FriendlyCaptcha;

class FriendlyCaptchaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->bootConfig();
        }

        $this->bootBladeDirectives();
    }

    /**
     * Boot config.
     */
    protected function bootConfig()
    {
        $path = __DIR__ . '/config/friendlycaptcha.php';

        if (function_exists('config_path')) {
            $this->publishes([$path => config_path('friendlycaptcha.php')]);
        }
    }

    public function bootBladeDirectives()
    {
        Blade::directive('friendlyCaptchaWidgetScriptsUnpkg', function () {
            return <<<EOF
                <script type="module" src="https://unpkg.com/friendly-challenge@0.9.8/widget.module.min.js" async defer></script>
                <script nomodule src="https://unpkg.com/friendly-challenge@0.9.8/widget.min.js" async defer></script>
            EOF;
        });

        Blade::directive('friendlyCaptchaWidgetScriptsJsdelivr', function () {
            return <<<EOF
                <script type="module" src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.8/widget.module.min.js" async defer></script>
                <script nomodule src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.8/widget.min.js" async defer></script>
            EOF;
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $path = __DIR__ . '/config/friendlycaptcha.php';

        $this->mergeConfigFrom($path, 'friendlycaptcha');

        $this->app->singleton('FriendlyCaptcha', function ($app) {
            return new FriendlyCaptcha(
                $app['config']['FriendlyCaptcha.secret'],
                $app['config']['FriendlyCaptcha.sitekey'],
                $app['config']['FriendlyCaptcha.options']
            );
        });

        $this->app->alias('FriendlyCaptcha', FriendlyCaptcha::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['FriendlyCaptcha'];
    }
}
