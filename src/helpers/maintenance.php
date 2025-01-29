<?php 

namespace Tsrgtm\MaintenanceMode\Helpers;

use Illuminate\Support\Facades\Config;

function setMaintenanceMode(bool $status)
{
    //update the .env file to persist the change
    $envPath = app()->environmentFilePath();
    file_put_contents($envPath, preg_replace(
        "/^MAINTENANCE_MODE=.*/m",
        "MAINTENANCE_MODE=" . ($status ? 'true' : 'false'),
        file_get_contents($envPath)
    ));

    // Clear the config cache to apply the change
    \Artisan::call('config:clear');
}

function isMaintenanceMode(): bool
{
    // Check the maintenance mode status
    return Config::get('maintenance.enabled') || (env('MAINTENANCE_MODE') === 'true');
}
