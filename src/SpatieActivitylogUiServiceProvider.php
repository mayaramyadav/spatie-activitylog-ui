<?php

namespace Mayaram\SpatieActivitylogUi;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class SpatieActivitylogUiServiceProvider extends ServiceProvider
{
    /**
     * Package version.
     */
    public const VERSION = '1.3.0';
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge package configuration
        $this->mergeConfigFrom(
            __DIR__ . '/config/spatie-activitylog-ui.php',
            'spatie-activitylog-ui'
        );

        // Register services
        $this->app->singleton(\Mayaram\SpatieActivitylogUi\Services\ActivitylogService::class);
        $this->app->singleton(\Mayaram\SpatieActivitylogUi\Services\AnalyticsService::class);
        $this->app->singleton(\Mayaram\SpatieActivitylogUi\Services\ExportService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'spatie-activitylog-ui');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Define Gate for package access control
        Gate::define('viewActivityLogUi', function ($user = null) {
            // Check if user has permission to view activity log UI
            // You can customize this logic based on your needs
            if ($user === null) {
                return false;
            }

            // Check config for allowed users/roles
            $allowedUsers = config('spatie-activitylog-ui.access.allowed_users', []);
            $allowedRoles = config('spatie-activitylog-ui.access.allowed_roles', []);

            // If no restrictions are set, allow all authenticated users
            if (empty($allowedUsers) && empty($allowedRoles)) {
                return true;
            }

            // Check if user is in allowed users list
            if (!empty($allowedUsers) && in_array($user->email, $allowedUsers)) {
                return true;
            }

            // Check if user has any of the allowed roles
            if (!empty($allowedRoles) && method_exists($user, 'hasAnyRole')) {
                return $user->hasAnyRole($allowedRoles);
            }

            // Check if user has role method and any allowed role
            if (!empty($allowedRoles) && method_exists($user, 'hasRole')) {
                foreach ($allowedRoles as $role) {
                    if ($user->hasRole($role)) {
                        return true;
                    }
                }
            }

            return false;
        });

        // Register publishable resources
        $this->registerPublishing();

        // Register middleware if needed
        $this->registerMiddleware();

        // Register commands if any
        $this->registerCommands();
    }

    /**
     * Register middleware for the package.
     */
    protected function registerMiddleware(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('activitylog-access', \Mayaram\SpatieActivitylogUi\Http\Middleware\ActivityLogAccessMiddleware::class);
    }

    /**
     * Register artisan commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            // Register commands here if any
            // $this->commands([
            //     \Mayaram\SpatieActivitylogUi\Console\Commands\InstallCommand::class,
            // ]);
        }
    }

    /**
     * Register publishable resources.
     */
    protected function registerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/config/spatie-activitylog-ui.php' => config_path('spatie-activitylog-ui.php'),
        ], 'spatie-activitylog-ui-config');

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/spatie-activitylog-ui'),
        ], 'spatie-activitylog-ui-views');

        // Publish images (logo, favicon, etc.)
        $this->publishes([
            __DIR__ . '/resources/images' => public_path('vendor/spatie-activitylog-ui/images'),
        ], 'spatie-activitylog-ui-assets');

        // Publish CSS assets if they exist
        if (is_dir(__DIR__ . '/resources/css')) {
            $this->publishes([
                __DIR__ . '/resources/css' => public_path('vendor/spatie-activitylog-ui/css'),
            ], 'spatie-activitylog-ui-assets');
        }

        // Publish JS assets if they exist
        if (is_dir(__DIR__ . '/resources/js')) {
            $this->publishes([
                __DIR__ . '/resources/js' => public_path('vendor/spatie-activitylog-ui/js'),
            ], 'spatie-activitylog-ui-assets');
        }
    }
}
