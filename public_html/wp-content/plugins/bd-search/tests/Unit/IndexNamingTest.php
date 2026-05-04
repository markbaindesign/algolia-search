<?php

namespace BDSearch\Tests\Unit;

/**
 * Tests for Algolia index name construction.
 *
 * Index names are composed of a WordPress table prefix plus the logical index
 * name and an optional language suffix. Getting this wrong silently indexes
 * into the wrong Algolia index.
 *
 * @see inc/algolia/al-helpers/bd324_algolia_get_full_index_name.php
 * @see inc/algolia/al-filters/bd324_add_table_prefix_to_index_name.php
 */
class IndexNamingTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        global $table_prefix;
        $table_prefix = 'wp_';
    }

    // -------------------------------------------------------
    // bd324_algolia_get_full_index_name
    // -------------------------------------------------------

    public function test_full_name_with_prefix_no_language(): void
    {
        $result = bd324_algolia_get_full_index_name('global');
        $this->assertSame('wp_global', $result);
    }

    public function test_full_name_with_prefix_and_language(): void
    {
        $result = bd324_algolia_get_full_index_name('global', 'fr');
        $this->assertSame('wp_global_fr', $result);
    }

    public function test_full_name_empty_language_omits_suffix(): void
    {
        $result = bd324_algolia_get_full_index_name('posts', '');
        $this->assertSame('wp_posts', $result);
    }

    public function test_full_name_empty_prefix(): void
    {
        global $table_prefix;
        $table_prefix = '';

        $result = bd324_algolia_get_full_index_name('global');
        $this->assertSame('global', $result);
    }

    public function test_full_name_custom_prefix(): void
    {
        global $table_prefix;
        $table_prefix = 'client_';

        $result = bd324_algolia_get_full_index_name('products', 'de');
        $this->assertSame('client_products_de', $result);
    }

    // -------------------------------------------------------
    // bd324_add_table_prefix_to_index_name
    // -------------------------------------------------------

    public function test_table_prefix_prepended(): void
    {
        $result = bd324_add_table_prefix_to_index_name('global');
        $this->assertSame('wp_global', $result);
    }

    public function test_table_prefix_empty_returns_name_unchanged(): void
    {
        global $table_prefix;
        $table_prefix = '';

        $result = bd324_add_table_prefix_to_index_name('global');
        $this->assertSame('global', $result);
    }
}
