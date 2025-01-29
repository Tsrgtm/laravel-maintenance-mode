# Laravel Maintenance Mode Package

## Introduction

The Laravel Maintenance Mode package provides an easy way to manage maintenance mode in your Laravel application. It includes commands for toggling maintenance mode, checking the current status, and ensuring that the necessary environment variable is set in the `.env` file.

## Features

- Toggle maintenance mode on or off using Artisan commands.
- Check if maintenance mode is enabled or disabled.
- Automatically adds `MAINTENANCE_MODE` to the `.env` file if it doesn't exist.
- Publishable configuration and view files.

## Installation

You can install the package via Composer. Run the following command:

```bash
composer require tsrgtm/maintenance-mode
```

## Middleware

### Register Middleware in Laravel 11.x

If you want a middleware to run during every HTTP request to your application, you may append it to the global middleware stack in your application's 'bootstrap/app.php' file:

```php
use Tsrgtm\MaintenanceMode\Middleware\MaintenanceModeMiddleware;

->withMiddleware(function (Middleware $middleware) {
     $middleware->append(MaintenanceModeMiddleware::class);
})
```

OR

If you would like to assign middleware to specific routes, you may invoke the middleware method when defining the route:

```php
use Tsrgtm\MaintenanceMode\Middleware\MaintenanceModeMiddleware;

Route::get('/profile', function () {
    // ...
})->middleware(MaintenanceModeMiddleware::class);
```

### Publish Configuration and Views

To publish the configuration and view files, run:

```bash
php artisan vendor:publish --tag=config
php artisan vendor:publish --tag=views
```

## Configuration

After publishing, you can find the configuration file at `config/maintenance.php`. In this file, you can control the following settings:

- **enabled**: This option determines whether the maintenance mode is enabled or disabled. It retrieves its value from the `.env` file using the `MAINTENANCE_MODE` environment variable. If the variable is not set, it defaults to `false` (maintenance mode is off).

  ```php
  'enabled' => env('MAINTENANCE_MODE', false),
  ```

- **view**: This specifies the default view that should be displayed when the application is in maintenance mode. By default, it uses the `maintenance::default` view, which can be customized in your application.

  ```php
  'view' => 'maintenance::default',
  ```

  **Example**:

  ```php
  'view' => 'components.custom-maintenance', //user can define custom view
  ```

- **allowed_routes**: This is an array where you can define specific route names that should remain accessible even when the application is in maintenance mode. For example, you might want to allow access to a maintenance notification route or an emergency contact route.

  ```php
  'allowed_routes' => [],
  ```

  **Example**:

  ```php
  'allowed_routes' => ['login', 'register', 'about'], // Users can access these routes
  ```

- **allowed_urls**: Similar to `allowed_routes`, this array allows you to specify certain URLs that can be accessed during maintenance mode. This is useful for providing access to API endpoints or other critical resources while the rest of the application is offline.

  ```php
  'allowed_urls' => [],
  ```

  **Example**:

  ```php
  'allowed_urls' => ['/api/v1/health-check', '/support/contact'], // Specific API endpoints that remain accessible
  ```

## Commands

### Toggle Maintenance Mode

You can enable or disable maintenance mode using the following command:

```bash
php artisan maintenance:toggle {status}
```

Replace `{status}` with `true` to enable maintenance mode or `false` to disable it.

### Example

```bash
php artisan maintenance:toggle true  # Enable maintenance mode
php artisan maintenance:toggle false # Disable maintenance mode
```

## Helper Functions

### Set Maintenance Mode

You can set maintenance mode using the helper function:

```php
use Tsrgtm\MaintenanceMode\Helpers\setMaintenanceMode;

setMaintenanceMode(true);  // Enable maintenance mode
setMaintenanceMode(false); // Disable maintenance mode
```

### Check Maintenance Mode Status

You can check if maintenance mode is on or off using the helper function:

```php
use Tsrgtm\MaintenanceMode\Helpers\isMaintenanceMode;

if (isMaintenanceMode()) {
    echo "The application is currently in maintenance mode.";
} else {
    echo "The application is running normally.";
}
```

### Bypass Access for Users

To allow certain users to bypass maintenance mode, you can add a method in your User model. Here’s how:

```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public function bypassMaintenance(): bool
    {
        return $this->is_admin; // Modify as needed (e.g., check a role)
    }

}
```

When the middleware runs, it can check this method to determine if the user should have access during maintenance mode.

## License

This package is open-source and available under the MIT License.

## Contributing

If you'd like to contribute to this package, please fork the repository and submit a pull request.

## Author

This package is maintained by [Your Name](https://github.com/tsrgtm).
