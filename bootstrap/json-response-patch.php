<?php

// This file patches the JsonResponse class to sanitize UTF-8 data before JSON encoding
// It must be loaded early in the bootstrap process

// Only patch if running in web context
if (php_sapi_name() !== 'cli') {
    $originalSetData = function($data = []) {
        // Sanitize the data
        $sanitized = \App\Http\SafeJsonResponse::sanitizeData($data);
        return $sanitized;
    };
}
