<?php

namespace Zeynallow\Hashtags;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Zeynallow\Hashtags\Services\HashtagService;

class LaravelHashtagsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        // Publish config file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/laravel-hashtags.php' => config_path('laravel-hashtags.php'),
            ], 'laravel-hashtags-config');

            // Publish migrations
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'laravel-hashtags-migrations');
        }

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register Blade directives
        $this->registerBladeDirectives();

        // Load helpers
        if (!function_exists('tagify')) {
            require_once __DIR__ . '/helpers.php';
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/config/laravel-hashtags.php',
            'laravel-hashtags'
        );

        // Register services
        $this->app->singleton(HashtagService::class, function ($app) {
            return new HashtagService();
        });

        $this->app->singleton(HashtagExtractor::class, function ($app) {
            return new HashtagExtractor();
        });
    }

    /**
     * Register Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('hashtags', function ($expression) {
            return "<?php echo tagify($expression); ?>";
        });

        Blade::directive('hashtagLinks', function ($expression) {
            return "<?php echo tagify($expression, true); ?>";
        });
    }
}
