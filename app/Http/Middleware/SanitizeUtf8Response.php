<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeUtf8Response
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only sanitize JSON responses
        if ($response->headers->get('Content-Type') && str_contains($response->headers->get('Content-Type'), 'application/json')) {
            $content = $response->getContent();
            if (is_string($content) && !empty($content)) {
                try {
                    // Try to decode and re-encode with UTF-8 fixing
                    $decoded = json_decode($content, true, 512, JSON_UNESCAPED_SLASHES);
                    if ($decoded !== null) {
                        // Fix UTF-8 characters in the decoded data
                        $sanitized = $this->fixUtf8Recursive($decoded);
                        // Re-encode the data
                        $encoded = json_encode($sanitized, JSON_UNESCAPED_SLASHES);
                        if ($encoded !== false) {
                            $response->setContent($encoded);
                        }
                    }
                } catch (\Exception $e) {
                    // If JSON operations fail, try to sanitize the raw content
                    $sanitized = $this->sanitizeUtf8String($content);
                    $response->setContent($sanitized);
                }
            }
        }

        return $response;
    }

    /**
     * Fix UTF-8 characters recursively in an array/object.
     */
    private function fixUtf8Recursive($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data[$key] = $this->fixUtf8Recursive($value);
                } elseif (is_string($value)) {
                    $data[$key] = $this->fixUtf8String($value);
                }
            }
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data->$key = $this->fixUtf8Recursive($value);
                } elseif (is_string($value)) {
                    $data->$key = $this->fixUtf8String($value);
                }
            }
        }
        return $data;
    }

    /**
     * Fix UTF-8 characters in a string.
     */
    private function fixUtf8String(string $string): string
    {
        // Convert encoding to UTF-8, replacing invalid sequences
        return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    }

    /**
     * Sanitize UTF-8 characters in raw JSON string.
     */
    private function sanitizeUtf8String(string $string): string
    {
        return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    }
}
