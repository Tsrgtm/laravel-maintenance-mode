# 🔧 Laravel Maintenance Mode Package

_A robust solution for managing maintenance mode in Laravel applications with fine-grained control and elegant customization_

---

## 📖 Table of Contents

1. [Introduction](#-introduction)
2. [Key Features](#-key-features)
3. [Installation](#-installation)
4. [Configuration](#-configuration)  
   4.1 [Middleware Setup](#middleware-setup)  
   4.2 [Publishing Assets](#publishing-assets)  
   4.3 [Configuration Options](#configuration-options)
5. [Usage](#-usage)  
   5.1 [Artisan Commands](#artisan-commands)  
   5.2 [Helper Functions](#helper-functions)  
   5.3 [Bypass Mechanism](#bypass-mechanism)
6. [Customization](#-customization)
7. [Contributing](#-contributing)
8. [License](#-license)
9. [Author](#-author)

---

## 🌟 Introduction

The Laravel Maintenance Mode package provides enterprise-grade maintenance mode management for Laravel applications. Features include environment-based configuration, granular access control, and seamless integration with Laravel's ecosystem.

---

## 🚩 Key Features

- ⚡ Instant toggle via Artisan commands
- 🔐 Role-based access control
- 🛣 Route/URL whitelisting
- 🎭 Customizable maintenance pages
- 📊 Environment variable sync
- 👮 Middleware access control
- 🔍 Real-time status checks

---

## 🚀 Installation

```bash
composer require tsrgtm/maintenance-mode
```

---

## 🛠 Configuration

### Middleware Setup

**Global registration (Laravel 11+)** in `bootstrap/app.php`:

```php
use Tsrgtm\MaintenanceMode\Middleware\MaintenanceModeMiddleware;

->withMiddleware(function (Middleware $middleware) {
    $middleware->append(MaintenanceModeMiddleware::class);
})
```

**Route-specific registration**:

```php
Route::get('/admin', fn() => view('admin'))->middleware(MaintenanceModeMiddleware::class);
```

### Publishing Assets

```bash
php artisan vendor:publish --tag=config
php artisan vendor:publish --tag=views
```

### Configuration Options

`config/maintenance.php`:

```php
return [
    'enabled' => env('MAINTENANCE_MODE', false),
    'view' => 'maintenance::default',
    'allowed_routes' => ['login', 'status'],
    'allowed_urls' => ['/api/healthcheck'],
];
```

| Option         | Type   | Default                | Description                               |
| -------------- | ------ | ---------------------- | ----------------------------------------- |
| enabled        | bool   | `false`                | Maintenance mode status                   |
| view           | string | `maintenance::default` | Blade template path                       |
| allowed_routes | array  | `[]`                   | Accessible route names during maintenance |
| allowed_urls   | array  | `[]`                   | Accessible URL paths during maintenance   |

---

## 🎮 Usage

### Artisan Commands

```bash
# Toggle maintenance mode
php artisan maintenance:toggle true   # Enable
php artisan maintenance:toggle false  # Disable

# Check status
php artisan maintenance:status
```

### Helper Functions

```php
use Tsrgtm\MaintenanceMode\Helpers\{setMaintenanceMode, isMaintenanceMode};

// Enable maintenance mode
setMaintenanceMode(true);

// Check status
if (isMaintenanceMode()) {
    // Maintenance mode logic
}
```

### Bypass Mechanism

Add to `User` model:

```php
class User extends Authenticatable
{
    public function canBypassMaintenance(): bool
    {
        return $this->is_admin || $this->hasRole('maintenance-tech');
    }
}
```

---

## 🎨 Customization

1. Create custom view:

```bash
resources/views/vendor/maintenance/custom.blade.php
```

2. Update configuration:

```php
'view' => 'vendor.maintenance.custom',
```

**Example view**:

```html
@extends('layouts.app') @section('content')
<div class="container">
  <h1>🔧 Scheduled Maintenance</h1>
  <p>We expect to be back by {{ $estimatedTime }}</p>
  <div class="alert alert-info">Contact support: support@example.com</div>
</div>
@endsection
```

---

## 🤝 Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature/new-feature`
3. Commit changes: `git commit -m 'Add new feature'`
4. Push to branch: `git push origin feature/new-feature`
5. Open Pull Request

---

## 📜 License

MIT License - See [LICENSE](LICENSE) for details.

---

## 👤 Author

**Tusar Gautam**  
[GitHub Profile](https://github.com/tsrgtm) • [Email](mailto:mailme@tusargautam.com.np)
