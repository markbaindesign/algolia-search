#!/usr/bin/env php
<?php

/**
 * Simple test runner for BD Search Plugin
 * Tests basic functionality without requiring DOM extensions
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/tests/bootstrap.php';

echo "BD324 debug: Starting simple test runner\n";

// Test 1: Check if bd324_debug_log function exists
if (function_exists('bd324_debug_log')) {
    echo "✅ BD324 debug: bd324_debug_log function exists\n";
} else {
    echo "❌ BD324 debug: bd324_debug_log function missing\n";
}

// Test 2: Check constants
$required_constants = [
    'BD324_DEBUG',
    'BD616__PLUGIN_DIR',
    'ALGOLIA_APPLICATION_ID',
    'ALGOLIA_SEARCH_API_KEY'
];

foreach ($required_constants as $constant) {
    if (defined($constant)) {
        echo "✅ BD324 debug: Constant {$constant} is defined\n";
    } else {
        echo "❌ BD324 debug: Constant {$constant} is missing\n";
    }
}

// Test 3: Test bd324_debug_log function
echo "BD324 debug: Testing bd324_debug_log function...\n";
bd324_debug_log('Test message from simple test runner', ['test' => true]);

// Test 4: Check if algolia_load_assets function exists
if (function_exists('algolia_load_assets')) {
    echo "✅ BD324 debug: algolia_load_assets function exists\n";
} else {
    echo "❌ BD324 debug: algolia_load_assets function missing\n";
}

echo "BD324 debug: Simple test runner completed\n";
echo "BD324 debug: To run full PHPUnit tests, install missing PHP extensions:\n";
echo "BD324 debug: sudo apt-get install php8.0-dom php8.0-xml php8.0-mbstring\n";
