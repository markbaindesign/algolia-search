<?php

namespace BDSearch\Tests\Unit;

/**
 * Tests for bd324_get_args_for_query.
 *
 * This function builds the WP_Query arguments used during bulk Algolia indexing.
 * Incorrect defaults or missing keys cause silent indexing gaps.
 *
 * @see inc/algolia/updates/get_args_for_query.php
 */
class QueryArgsTest extends BaseTestCase
{
    public function test_returns_array(): void
    {
        $args = bd324_get_args_for_query('global', '', ['post', 'page']);
        $this->assertIsArray($args);
    }

    public function test_default_post_status_is_publish(): void
    {
        $args = bd324_get_args_for_query('global', '', ['post']);
        $this->assertSame('publish', $args['post_status']);
    }

    public function test_default_has_password_is_false(): void
    {
        $args = bd324_get_args_for_query('global', '', ['post']);
        $this->assertFalse($args['has_password']);
    }

    public function test_default_posts_per_page_is_100(): void
    {
        $args = bd324_get_args_for_query('global', '', ['post']);
        $this->assertSame(100, $args['posts_per_page']);
    }

    public function test_post_types_passed_through(): void
    {
        $types = ['post', 'page', 'event'];
        $args  = bd324_get_args_for_query('global', '', $types);
        $this->assertSame($types, $args['post_type']);
    }

    public function test_paged_defaults_to_1(): void
    {
        $args = bd324_get_args_for_query('global', '', ['post']);
        $this->assertSame(1, $args['paged']);
    }

    public function test_paged_param_passed_through(): void
    {
        $args = bd324_get_args_for_query('global', '', ['post'], [], 3);
        $this->assertSame(3, $args['paged']);
    }

    public function test_required_keys_present(): void
    {
        $args     = bd324_get_args_for_query('global', 'en', ['post']);
        $required = ['posts_per_page', 'paged', 'post_type', 'post_status', 'has_password'];
        foreach ($required as $key) {
            $this->assertArrayHasKey($key, $args, "Missing key: $key");
        }
    }
}
