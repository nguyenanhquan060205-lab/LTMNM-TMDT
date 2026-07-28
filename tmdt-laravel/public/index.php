<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

try {
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>CRITICAL ERROR:</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    if (file_exists(__DIR__.'/../storage/logs/laravel.log')) {
        echo "<h3>Logs:</h3>";
        echo "<pre>" . file_get_contents(__DIR__.'/../storage/logs/laravel.log') . "</pre>";
    }
}
