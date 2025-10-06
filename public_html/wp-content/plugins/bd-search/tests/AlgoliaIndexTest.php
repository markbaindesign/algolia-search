<?php

/**
 * Tests for Algolia Index Name Functions
 *
 * @package BDSearch\Tests
 */

namespace BDSearch\Tests;

use PHPUnit\Framework\TestCase;
use Brain\Monkey\Functions;
use Yoast\PHPUnitPolyfills\TestCases\TestCase as PolyfillTestCase;

class AlgoliaIndexTest extends PolyfillTestCase
{
    /**
     * Setup before each test
     */
    protected function set_up(): void
    {
        parent::set_up();
        \Brain\Monkey\setUp();

        // Mock global $algolia
        global $algolia;
        $algolia = (object) ['index' => 'test_index'];
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
     * Test get_algolia_indexName function exists
     */
    public function test_get_algolia_indexName_function_exists(): void
    {
        $this->assertTrue(
            function_exists('get_algolia_indexName'),
            'BD324 debug: get_algolia_indexName function should exist'
        );
        echo "BD324 debug: get_algolia_indexName function exists test passed\n";
    }

    /**
     * Test get_algolia_indexName with valid post ID
     */
    public function test_get_algolia_indexName_with_valid_post(): void
    {
        // Mock WordPress functions
        Functions\when('get_post_type')->justReturn('post');
        Functions\when('apply_filters')->justReturn(['language_code' => 'en']);
        Functions\when('is_wp_error')->justReturn(false);

        // Test function call
        $result = get_algolia_indexName(123);

        // Should return a string
        $this->assertIsString($result);

        echo "BD324 debug: get_algolia_indexName with valid post test passed\n";
    }

    /**
     * Test get_algolia_indexName with WPML language
     */
    public function test_get_algolia_indexName_with_wpml(): void
    {
        // Mock WPML functions
        Functions\when('get_post_type')->justReturn('page');
        Functions\when('apply_filters')->justReturn(['language_code' => 'zh']);
        Functions\when('is_wp_error')->justReturn(false);

        $result = get_algolia_indexName(456);

        $this->assertIsString($result);

        echo "BD324 debug: get_algolia_indexName with WPML test passed\n";
    }

    /**
     * Test bd324_get_algolia_index_name function exists
     */
    public function test_bd324_get_algolia_index_name_function_exists(): void
    {
        $this->assertTrue(
            function_exists('bd324_get_algolia_index_name'),
            'BD324 debug: bd324_get_algolia_index_name function should exist'
        );
        echo "BD324 debug: bd324_get_algolia_index_name function exists test passed\n";
    }

    /**
     * Test algolia_post_index_name function exists
     */
    public function test_algolia_post_index_name_function_exists(): void
    {
        $this->assertTrue(
            function_exists('algolia_post_index_name'),
            'BD324 debug: algolia_post_index_name function should exist'
        );
        echo "BD324 debug: algolia_post_index_name function exists test passed\n";
    }

    /**
     * Test index name generation with different post types
     */
    public function test_index_name_generation_different_post_types(): void
    {
        $post_types = ['post', 'page', 'product', 'event'];

        foreach ($post_types as $post_type) {
            Functions\when('get_post_type')->justReturn($post_type);
            Functions\when('apply_filters')->justReturn(['language_code' => 'en']);
            Functions\when('is_wp_error')->justReturn(false);

            $result = get_algolia_indexName(123);

            $this->assertIsString($result);
            $this->assertNotEmpty($result);

            echo "BD324 debug: Index name generation for {$post_type} test passed\n";
        }
    }

    /**
     * Test error handling for invalid post ID
     */
    public function test_error_handling_invalid_post_id(): void
    {
        Functions\when('get_post_type')->justReturn(false);
        Functions\when('apply_filters')->justReturn(new \WP_Error('invalid_post'));
        Functions\when('is_wp_error')->justReturn(true);

        $result = get_algolia_indexName(0);

        // Should handle gracefully
        $this->assertIsString($result);

        echo "BD324 debug: Error handling for invalid post ID test passed\n";
    }
}
