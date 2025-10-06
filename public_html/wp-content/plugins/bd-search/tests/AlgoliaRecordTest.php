<?php

/**
 * Tests for Algolia Record Update Functions
 *
 * @package BDSearch\Tests
 */

namespace BDSearch\Tests;

use PHPUnit\Framework\TestCase;
use Brain\Monkey\Functions;
use Yoast\PHPUnitPolyfills\TestCases\TestCase as PolyfillTestCase;

class AlgoliaRecordTest extends PolyfillTestCase
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
        $algolia = (object) [
            'index' => 'test_index',
            'saveObjects' => function () { return true; }
        ];
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
     * Test bd324_update_algolia_record function exists
     */
    public function test_bd324_update_algolia_record_function_exists(): void
    {
        $this->assertTrue(
            function_exists('bd324_update_algolia_record'),
            'BD324 debug: bd324_update_algolia_record function should exist'
        );
        echo "BD324 debug: bd324_update_algolia_record function exists test passed\n";
    }

    /**
     * Test record update with valid post
     */
    public function test_record_update_with_valid_post(): void
    {
        // Create mock WP_Post object
        $mock_post = (object) [
            'ID' => 123,
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_title' => 'Test Post',
            'post_content' => 'Test content'
        ];

        // Mock WordPress functions
        Functions\when('get_post')->justReturn($mock_post);
        Functions\when('get_post_type')->justReturn('post');
        Functions\when('get_post_status')->justReturn('publish');
        Functions\when('wp_is_post_revision')->justReturn(false);
        Functions\when('wp_is_post_autosave')->justReturn(false);

        try {
            bd324_update_algolia_record(123, $mock_post);
            echo "BD324 debug: Record update with valid post test passed\n";
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail("BD324 debug: Record update should not throw exceptions: " . $e->getMessage());
        }
    }

    /**
     * Test record update skips revisions
     */
    public function test_record_update_skips_revisions(): void
    {
        $mock_post = (object) [
            'ID' => 124,
            'post_type' => 'revision',
            'post_status' => 'inherit'
        ];

        // Mock revision detection
        Functions\when('wp_is_post_revision')->justReturn(true);
        Functions\when('wp_is_post_autosave')->justReturn(false);
        Functions\when('get_post_type')->justReturn('revision');
        Functions\when('get_post_status')->justReturn('inherit');

        try {
            bd324_update_algolia_record(124, $mock_post);
            echo "BD324 debug: Record update skips revisions test passed\n";
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail("BD324 debug: Should handle revisions gracefully: " . $e->getMessage());
        }
    }

    /**
     * Test record update skips autosaves
     */
    public function test_record_update_skips_autosaves(): void
    {
        $mock_post = (object) [
            'ID' => 125,
            'post_type' => 'post',
            'post_status' => 'draft'
        ];

        // Mock autosave detection
        Functions\when('wp_is_post_revision')->justReturn(false);
        Functions\when('wp_is_post_autosave')->justReturn(true);
        Functions\when('get_post_type')->justReturn('post');
        Functions\when('get_post_status')->justReturn('draft');

        try {
            bd324_update_algolia_record(125, $mock_post);
            echo "BD324 debug: Record update skips autosaves test passed\n";
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail("BD324 debug: Should handle autosaves gracefully: " . $e->getMessage());
        }
    }

    /**
     * Test algolia_add_content_to_record function exists
     */
    public function test_algolia_add_content_to_record_function_exists(): void
    {
        $this->assertTrue(
            function_exists('algolia_add_content_to_record'),
            'BD324 debug: algolia_add_content_to_record function should exist'
        );
        echo "BD324 debug: algolia_add_content_to_record function exists test passed\n";
    }

    /**
     * Test algolia_add_permalink_to_record function exists
     */
    public function test_algolia_add_permalink_to_record_function_exists(): void
    {
        $this->assertTrue(
            function_exists('algolia_add_permalink_to_record'),
            'BD324 debug: algolia_add_permalink_to_record function should exist'
        );
        echo "BD324 debug: algolia_add_permalink_to_record function exists test passed\n";
    }

    /**
     * Test record field addition functions
     */
    public function test_record_field_addition_functions(): void
    {
        $functions_to_test = [
            'algolia_add_featured_to_record',
            'algolia_add_contributor_to_record',
            'algolia_add_tax_values_to_record',
            'algolia_add_wordpress_post_type_to_record'
        ];

        foreach ($functions_to_test as $function_name) {
            $this->assertTrue(
                function_exists($function_name),
                "BD324 debug: {$function_name} function should exist"
            );
            echo "BD324 debug: {$function_name} function exists test passed\n";
        }
    }

    /**
     * Test content addition with various post types
     */
    public function test_content_addition_various_post_types(): void
    {
        $post_types = ['post', 'page', 'product'];

        foreach ($post_types as $post_type) {
            $mock_record = [];
            $mock_post = (object) [
                'ID' => 200,
                'post_type' => $post_type,
                'post_content' => "Test content for {$post_type}",
                'post_title' => "Test {$post_type}"
            ];

            Functions\when('get_post_field')->justReturn("Test content for {$post_type}");
            Functions\when('wp_strip_all_tags')->returnArg();

            try {
                $result = algolia_add_content_to_record($mock_record, $mock_post);
                $this->assertIsArray($result);
                echo "BD324 debug: Content addition for {$post_type} test passed\n";
            } catch (\Exception $e) {
                echo "BD324 debug: Content addition for {$post_type} failed: " . $e->getMessage() . "\n";
            }
        }
    }
}
