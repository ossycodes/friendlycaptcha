<?php

namespace Ossycodes\FriendlyCaptcha;

use Illuminate\Validation\Rule;
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

        $this->bootMacro();

        $this->bootLang();
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

    /**
     * Boot blade directives
     */
    public function bootBladeDirectives()
    {
        Blade::directive('friendlyCaptchaRenderWidgetScripts', function () {
            return <<<EOF
                    <script type="module" src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.1.36/site.min.js" async defer></script>
                    <script nomodule src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.1.36/site.compat.min.js" async defer></script>
                EOF;
        });
    }

    /**
     * boot macro
     */
    public function bootMacro()
    {
        Rule::macro('friendlycaptcha', function () {
            return app(\Ossycodes\FriendlyCaptcha\Rules\FriendlyCaptcha::class);
        });
    }

    /**
     * boot lang
     */
    public function bootLang()
    {
        Rule::macro('friendlycaptcha', function () {
            $this->loadTranslationsFrom(__DIR__.'/../lang', 'friendlycaptcha');
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
                $app['config']['friendlycaptcha.secret'],
                $app['config']['friendlycaptcha.sitekey'],
                $app['config']['friendlycaptcha.puzzle_endpoint'],
                $app['config']['friendlycaptcha.verify_endpoint'],
                $app['config']['friendlycaptcha.options']
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
