<?php

namespace App\Http;

use Illuminate\Routing\Router as BaseRouter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SafeRouter extends BaseRouter
{
    /**
     * Convert a value to a response.
     */
    public static function toResponse($request, $response)
    {
        // If response is an array, use SafeJsonResponse instead of JsonResponse
        if (is_array($response)) {
            return new SafeJsonResponse($response);
        }

        // For other types, use the parent implementation
        return parent::toResponse($request, $response);
    }
}
