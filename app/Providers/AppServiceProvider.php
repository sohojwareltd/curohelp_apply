<?php

namespace App\Providers;

use App\Models\Worker;
use App\Observers\WorkerObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Router;
use App\Http\SafeJsonResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind SafeJsonResponse as the default JsonResponse
        $this->app->bind(JsonResponse::class, SafeJsonResponse::class);

        // Bind custom login response for role-based redirect
        $this->app->singleton(
            \Filament\Auth\Http\Responses\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        // Also try to intercept the Router's static method by wrapping it
        // We do this in register so it happens as early as possible
        $this->patchRouter();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observer for Worker model to handle invoice upload notifications
        Worker::observe(WorkerObserver::class);
    }

    /**
     * Patch the Router class's static toResponse method at runtime.
     */
    private function patchRouter(): void
    {
        // Use Reflection to patch the Router's toResponse method
        $reflection = new \ReflectionClass(Router::class);
        $method = $reflection->getMethod('toResponse');
        
        // Unfortunately, static method patching isn't possible via Reflection
        // So we'll use a different approach: override via container binding
        
        // When the router needs to create a response, it'll use our SafeJsonResponse
        // by ensuring all JsonResponse instantiations go through our class
        
        // This works because the Router calls: new JsonResponse(...)
        // And we've bound JsonResponse to SafeJsonResponse in the container
        
        // For extra safety, we'll also patch the Response factory
        $this->app->singleton('response', function ($app) {
            $factory = new \Illuminate\Routing\ResponseFactory(
                $app['view'],
                $app['redirect'],
                $app['request']
            );

            // Override json() method to use SafeJsonResponse
            $factory->macro('json', function ($data = [], $status = 200, $headers = [], $options = 0) {
                return new SafeJsonResponse($data, $status, $headers, false);
            });

            return $factory;
        });
    }
}
