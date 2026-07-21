<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$routes = Route::getRoutes();
$errors = [];

foreach ($routes as $route) {
    $action = $route->getAction();
    if (isset($action['controller'])) {
        $controllerStr = $action['controller'];
        if (strpos($controllerStr, '@') !== false) {
            list($class, $method) = explode('@', $controllerStr);
            if (!class_exists($class)) {
                $errors[] = "Class $class not found for route ".$route->uri();
            } elseif (!method_exists($class, $method)) {
                $errors[] = "Method $method not found in $class for route ".$route->uri();
            }
        }
    }
}

if (empty($errors)) {
    echo "All routes are valid!\n";
} else {
    echo "Route Errors Found:\n";
    foreach ($errors as $error) {
        echo "- $error\n";
    }
}
