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
} catch (InvalidArgumentException $e) {
    // Catch UTF-8 encoding errors from JsonResponse and return a safe response
    if (str_contains($e->getMessage(), 'Malformed UTF-8 characters')) {
        header('Content-Type: application/json', true, 500);
        echo json_encode([
            'error' => 'Data encoding error',
            'message' => 'The response data contains invalid UTF-8 characters that could not be processed.'
        ]);
        exit(1);
    }
    throw $e;
}
