<?php

namespace App\Http;

use Illuminate\Http\JsonResponse;

class SafeJsonResponse extends JsonResponse
{
    /**
     * Constructor - sanitize data before parent constructor tries to json_encode it.
     */
    public function __construct($data = [], int $status = 200, array $headers = [], bool $json = false)
    {
        // Sanitize the data BEFORE calling parent constructor
        $sanitized = $this->sanitizeUtf8($data);

        // Try to call parent with sanitized data
        try {
            parent::__construct($sanitized, $status, $headers, $json);
        } catch (\InvalidArgumentException $e) {
            // If still failing, use even more aggressive sanitization
            $aggressive = $this->aggressiveSanitizeUtf8($sanitized);
            parent::__construct($aggressive, $status, $headers, $json);
        }
    }

    /**
     * Sets the data to be sent as JSON.
     */
    public function setData($data = []): static
    {
        // Sanitize the data before setting it
        $sanitized = $this->sanitizeUtf8($data);
        
        try {
            parent::setData($sanitized);
        } catch (\InvalidArgumentException $e) {
            // If parent fails, try with aggressive sanitization
            $aggressive = $this->aggressiveSanitizeUtf8($sanitized);
            parent::setData($aggressive);
        }
        
        return $this;
    }

    /**
     * Public static method for sanitizing data (used by other classes).
     */
    public static function sanitizeData($data)
    {
        $instance = new self();
        return $instance->sanitizeUtf8($data);
    }

    /**
     * Sanitize UTF-8 characters recursively.
     */
    private function sanitizeUtf8($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data[$key] = $this->sanitizeUtf8($value);
                } elseif (is_string($value)) {
                    $data[$key] = $this->fixUtf8String($value);
                }
            }
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data->$key = $this->sanitizeUtf8($value);
                } elseif (is_string($value)) {
                    $data->$key = $this->fixUtf8String($value);
                }
            }
        }
        return $data;
    }

    /**
     * More aggressive UTF-8 sanitization that removes problematic characters entirely.
     */
    private function aggressiveSanitizeUtf8($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data[$key] = $this->aggressiveSanitizeUtf8($value);
                } elseif (is_string($value)) {
                    // Remove ALL invalid UTF-8 byte sequences
                    $data[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                    // Also try iconv as backup
                    if (!mb_check_encoding($data[$key], 'UTF-8')) {
                        $data[$key] = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
                    }
                }
            }
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data->$key = $this->aggressiveSanitizeUtf8($value);
                } elseif (is_string($value)) {
                    $data->$key = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                    if (!mb_check_encoding($data->$key, 'UTF-8')) {
                        $data->$key = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Fix invalid UTF-8 characters in a string.
     */
    private function fixUtf8String(string $value): string
    {
        // First check if it's already valid
        if (mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        // Try iconv with IGNORE flag to strip invalid bytes
        $fixed = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
        
        if ($fixed !== false) {
            return $fixed;
        }

        // Fallback to mb_convert_encoding
        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }
}
