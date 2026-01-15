<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\SanitizeUtf8Response;
use App\Http\Middleware\ValidateApiToken;
use Illuminate\Http\Middleware\HandleCors;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Enable CORS for cross-domain requests
        $middleware->append(HandleCors::class);
        $middleware->append(SanitizeUtf8Response::class);
        
        // Exclude CSRF verification for API routes
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);
        
        $middleware->alias([
            'role' => CheckUserRole::class,
            'validate.api.token' => ValidateApiToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    });

return $app->create();
