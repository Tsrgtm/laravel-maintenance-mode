<?php

return [
    'enabled' => env('MAINTENANCE_MODE', false),  // Get from .env
    'view' => 'maintenance::default', // Default maintenance page
    'allowed_routes' => [], // Allowed route names
    'allowed_urls' => [], // Allowed URLs
];
