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
    if (isset($_GET['debug_file'])) {
        echo "<pre>";
        echo ".env exists? " . (file_exists(__DIR__.'/../.env') ? 'YES' : 'NO') . "\n";
        if (file_exists(__DIR__.'/../.env')) {
            echo "Perms: " . substr(sprintf('%o', fileperms(__DIR__.'/../.env')), -4) . "\n";
            echo "Contents:\n" . file_get_contents(__DIR__.'/../.env');
        } else {
            echo "/etc/secrets/.env exists? " . (file_exists('/etc/secrets/.env') ? 'YES' : 'NO') . "\n";
        }
        echo "</pre>";
        exit;
    }
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
