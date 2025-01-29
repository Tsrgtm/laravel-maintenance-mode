<?php

namespace Tsrgtm\MaintenanceMode\Providers;

use Illuminate\Support\ServiceProvider;
use Tsrgtm\MaintenanceMode\Middleware\MaintenanceModeMiddleware;
use Tsrgtm\MaintenanceMode\Commands\ToggleMaintenanceCommand;

class MaintenanceModeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/maintenance.php', 'maintenance');
        $this->commands([
            ToggleMaintenanceCommand::class,
        ]);
        $this->addEnvVariable('MAINTENANCE_MODE', 'false');
    }

    public function boot()
    {
        // Load helper file
        if (file_exists($file = __DIR__.'/Helpers/maintenance.php')) {
            require $file;
        }

        $this->publishes([
            __DIR__.'/../../config/maintenance.php' => config_path('maintenance.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../views' => resource_path('views/vendor/maintenance'),
        ], 'views');

        $this->loadViewsFrom(__DIR__.'/../../views', 'maintenance');
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
