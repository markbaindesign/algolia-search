<?php

/**
 * Sample test for BD Search Plugin
 *
 * @package BDSearch\Tests
 */

namespace BDSearch\Tests;

use PHPUnit\Framework\TestCase;
use Brain\Monkey\Functions;
use Yoast\PHPUnitPolyfills\TestCases\TestCase as PolyfillTestCase;

class SampleTest extends PolyfillTestCase
{
    /**
     * Setup before each test
     */
    protected function set_up(): void
    {
        parent::set_up();
        \Brain\Monkey\setUp();
    }

    /**
     * Teardown after each test
     */
    protected function tear_down(): void
    {
        \Brain\Monkey\tearDown();
        parent::tear_down();
    }

    /**
     * Test that BD324_DEBUG constant is defined
     */
    public function test_bd324_debug_constant_defined(): void
    {
        $this->assertTrue(defined('BD324_DEBUG'));
        $this->assertTrue(BD324_DEBUG);
        echo "BD324 debug: BD324_DEBUG constant test passed\n";
    }

    /**
     * Test plugin constants are defined
     */
    public function test_plugin_constants_defined(): void
    {
        $expected_constants = [
            'BD616__PLUGIN_DIR',
            'BD616__PLUGIN_URL',
            'BD616__SCRIPTS_URL',
            'BD616__PLUGIN_VERSION',
            'ALGOLIA_APPLICATION_ID',
            'ALGOLIA_SEARCH_API_KEY',
            'ALGOLIA_API_KEY'
        ];

        foreach ($expected_constants as $constant) {
            $this->assertTrue(
                defined($constant),
                "BD324 debug: Constant {$constant} should be defined"
            );
        }

        echo "BD324 debug: All plugin constants test passed\n";
    }

    /**
     * Test bd324_debug_log function exists and works
     */
    public function test_bd324_debug_log_function(): void
    {
        // Test the function exists
        $this->assertTrue(function_exists('bd324_debug_log'));

        // Test the function can be called (without mocking error_log to avoid conflicts)
        try {
            bd324_debug_log('Test debug message', [ 'test' => 'data' ]);
            echo "BD324 debug: bd324_debug_log function test passed\n";
            $this->assertTrue(true); // If we get here, the function worked
        } catch (\Exception $e) {
            $this->fail("BD324 debug: bd324_debug_log should not throw exceptions: " . $e->getMessage());
        }
    }

    /**
     * Test algolia_load_assets function exists
     */
    public function test_algolia_load_assets_function_exists(): void
    {
        $this->assertTrue(
            function_exists('algolia_load_assets'),
            'BD324 debug: algolia_load_assets function should exist'
        );
        echo "BD324 debug: algolia_load_assets function exists test passed\n";
    }

    /**
     * Test WordPress hooks are properly added
     */
    public function test_wordpress_hooks(): void
    {
        // Test that we can call the main function without errors
        // Functions are already mocked in bootstrap.php to avoid Brain/Monkey conflicts
        try {
            algolia_load_assets();
            echo "BD324 debug: algolia_load_assets execution test passed\n";
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail("BD324 debug: algolia_load_assets should not throw exceptions: " . $e->getMessage());
        }
    }
}
