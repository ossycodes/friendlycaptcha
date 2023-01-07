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
        Blade::directive('friendlyCaptchaRenderWidgetScripts', function ($option) {
            $option = trim($option, "'");

            if (empty($option) || $option == 'unpkg') {
                return <<<EOF
                        <script type="module" src="https://unpkg.com/friendly-challenge@0.9.8/widget.module.min.js" async defer></script>
                        <script nomodule src="https://unpkg.com/friendly-challenge@0.9.8/widget.min.js" async defer></script>
                    EOF;
            }

            return <<<EOF
                    <script type="module" src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.8/widget.module.min.js" async defer></script>
                    <script nomodule src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.8/widget.min.js" async defer></script>
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
