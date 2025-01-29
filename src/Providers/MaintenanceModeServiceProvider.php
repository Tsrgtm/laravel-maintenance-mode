<?php

namespace Tsrgtm\MaintenanceMode\Providers;

use Illuminate\Support\ServiceProvider;
use Tsrgtm\MaintenanceMode\Middleware\MaintenanceModeMiddleware;
use Tsrgtm\MaintenanceMode\Commands\ToggleMaintenanceCommand;

class MaintenanceModeServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge the package configuration with the application's config
        $this->mergeConfigFrom(__DIR__.'/../../config/maintenance.php', 'maintenance');

        // Register the Artisan command
        $this->commands([
            ToggleMaintenanceCommand::class,
        ]);

        // Automatically add environment variable if it doesn't exist
        $this->addEnvVariable('MAINTENANCE_MODE', 'false');
    }

    public function boot()
    {
        // Load helper file if it exists
        if (file_exists($file = __DIR__.'/Helpers/maintenance.php')) {
            require $file;
        }

        // Publish configuration file with a unique name
        $this->publishes([
            __DIR__.'/../../config/maintenance.php' => config_path('maintenance_mode.php'),
        ], 'maintenance-config');

        // Publish view files with a unique name
        $this->publishes([
            __DIR__.'/../../views' => resource_path('views/vendor/maintenance_mode'),
        ], 'maintenance-views');

        // Load views from the package
        $this->loadViewsFrom(__DIR__.'/../../views', 'maintenance');

        // Register middleware
        $this->app['router']->aliasMiddleware('maintenance', MaintenanceModeMiddleware::class);
    }

    /**
     * Add an environment variable to the .env file if it doesn't exist.
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    protected function addEnvVariable(string $key, string $value)
    {
        $envPath = app()->environmentFilePath();
        $envContents = file_get_contents($envPath);

        // Check if the key already exists
        if (strpos($envContents, $key) === false) {
            file_put_contents($envPath, "\n$key=$value\n", FILE_APPEND);
        }
    }
}
