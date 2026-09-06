<?php

namespace RealRashid\SweetAlert;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use RealRashid\SweetAlert\Builders\AlertBuilder;
use RealRashid\SweetAlert\Builders\InputBuilder;
use RealRashid\SweetAlert\Builders\ToastBuilder;
use RealRashid\SweetAlert\Commands\InstallCommand;
use RealRashid\SweetAlert\Commands\PublishCommand;
use RealRashid\SweetAlert\Commands\UpgradeCommand;
use RealRashid\SweetAlert\Contracts\SessionStoreInterface;
use RealRashid\SweetAlert\Storage\AlertSessionStore;
use RealRashid\SweetAlert\Support\AlertFlasher;

/**
 * SweetAlertServiceProvider - Laravel service provider for the SweetAlert package.
 *
 * This provider registers the package's services, bindings, commands,
 * views, configuration, and publishable assets into the Laravel
 * application container.
 */
class SweetAlertServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->registerHelpers();
        $this->registerViews();
        $this->registerBladeDirectives();

        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
            $this->registerCommands();
        }
    }

    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sweet-alert.php', 'sweetalert');

        // Bind the session store interface to the implementation
        $this->app->bind(
            SessionStoreInterface::class,
            fn ($app) => new AlertSessionStore($app->make('session.store'))
        );

        // Register the AlertFlasher as a singleton
        $this->app->singleton(AlertFlasher::class, function ($app) {
            return new AlertFlasher($app->make(SessionStoreInterface::class));
        });

        // Register the main 'alert' binding (backward compatibility + new API)
        $this->app->singleton('alert', function ($app) {
            return $app->make(AlertBuilder::class);
        });

        // Register the AlertBuilder
        $this->app->singleton(AlertBuilder::class, function ($app) {
            return new AlertBuilder($app->make(AlertFlasher::class));
        });

        // Register the ToastBuilder
        $this->app->bind(ToastBuilder::class, function ($app) {
            return new ToastBuilder($app->make(AlertFlasher::class));
        });

        // Register the InputBuilder
        $this->app->bind(InputBuilder::class, function ($app) {
            return new InputBuilder($app->make(AlertFlasher::class));
        });
    }

    /**
     * Register the helper functions.
     */
    protected function registerHelpers(): void
    {
        if (file_exists($file = __DIR__.'/functions.php')) {
            require $file;
        }
    }

    /**
     * Register the package's views.
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sweetalert');
    }

    /**
     * Register the package's Blade directives.
     *
     * Provides a clean sweetAlert directive as the recommended way to include
     * SweetAlert2 in your layout, replacing the older include of the
     * sweetalert::alert view.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('sweetAlert', function () {
            return "<?php echo \$__env->make('sweetalert::alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>";
        });
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        // Publish config — filename must match the mergeConfigFrom key 'sweetalert'
        $this->publishes([
            __DIR__.'/../config/sweet-alert.php' => config_path('sweetalert.php'),
        ], 'sweetalert-config');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/sweetalert'),
        ], 'sweetalert-views');

        // Publish JS assets
        $this->publishes([
            __DIR__.'/../resources/js' => public_path('vendor/sweetalert'),
        ], 'sweetalert-asset');
    }

    /**
     * Register the package's artisan commands.
     */
    protected function registerCommands(): void
    {
        $this->commands([
            InstallCommand::class,
            PublishCommand::class,
            UpgradeCommand::class,
        ]);
    }
}
