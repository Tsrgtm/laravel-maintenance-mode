<?php

namespace Tsrgtm\MaintenanceMode\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;


class ToggleMaintenanceCommand extends Command
{
    protected $signature = 'maintenance:toggle {status}';
    protected $description = 'Enable or disable maintenance mode';

    public function handle()
    {
        $status = $this->argument('status') === 'true';

        // Update the .env file
        $this->updateEnvFile($status);

        // Reload config so changes take effect immediately
        Artisan::call('config:clear');

        $this->info('Maintenance mode ' . ($status ? 'enabled' : 'disabled'));
    }

    protected function updateEnvFile(bool $status)
    {
        $envPath = app()->environmentFilePath();
        file_put_contents($envPath, preg_replace(
            "/^MAINTENANCE_MODE=.*/m",
            "MAINTENANCE_MODE=" . ($status ? 'true' : 'false'),
            file_get_contents($envPath)
        ));
    }
}
