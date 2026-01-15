<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use App\Http\SafeJsonResponse;

class OverrideJsonResponseHandling
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Override the Router's static method before the response is created
        // We'll catch the response and wrap it if it's a JsonResponse
        $response = $next($request);

        // If the response is a JsonResponse but not our SafeJsonResponse, wrap it
        if ($response instanceof \Illuminate\Http\JsonResponse && !$response instanceof SafeJsonResponse) {
            try {
                // Get the content and try to decode it
                $content = $response->getContent();
                $data = json_decode($content, true, 512, JSON_UNESCAPED_SLASHES);
                
                // Create a new SafeJsonResponse with the data
                $safeResponse = new SafeJsonResponse($data, $response->getStatusCode(), $response->headers->all());
                return $safeResponse;
            } catch (\Exception $e) {
                // If something fails, return the original response
                return $response;
            }
        }

        return $response;
    }
}
