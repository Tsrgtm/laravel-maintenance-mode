<?php

namespace Tsrgtm\MaintenanceMode\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class MaintenanceModeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->isBypassed($request)) {
            return $next($request);
        }

        if ($this->isInMaintenance()) {
            return response()->view($this->getMaintenanceView(), [], 503);
        }

        return $next($request);
    }

    protected function isBypassed(Request $request): bool
    {
        if (Auth::check() && method_exists(Auth::user(), 'bypassMaintenance') && Auth::user()->bypassMaintenance()) {
            return true;
        }

        $allowedRoutes = config('maintenance.allowed_routes', []);
        if (in_array(Route::currentRouteName(), $allowedRoutes)) {
            return true;
        }

        $allowedUrls = config('maintenance.allowed_urls', []);
        if (in_array($request->path(), $allowedUrls)) {
            return true;
        }

        return false;
    }

    protected function isInMaintenance(): bool
    {
        return config('maintenance.enabled', false);
    }

    protected function getMaintenanceView(): string
    {
        return config('maintenance.view', 'maintenance::default');
    }
}
