<?php

// Test SafeJsonResponse
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

try {
    $response = new \App\Http\SafeJsonResponse(['test' => 'data', 'name' => 'test']);
    echo "✓ SafeJsonResponse created successfully\n";
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Content-Type: " . $response->headers->get('Content-Type') . "\n";
    echo "Body length: " . strlen($response->getContent()) . " bytes\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
