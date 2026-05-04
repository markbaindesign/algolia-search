<?php

namespace BDSearch\Tests\Unit;

/**
 * Tests for the content filter pipeline applied before Algolia indexing.
 *
 * @see inc/algolia/al-records/al-filters/add-filter-remove-divi.php
 * @see inc/algolia/al-records/al-filters/add-filter-truncate-content.php
 */
class ContentFiltersTest extends BaseTestCase
{
    private \WP_Post $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->post = new \WP_Post(['ID' => 1]);
    }

    // -------------------------------------------------------
    // bd324_filter_remove_divi_shortcodes_from_content
    // -------------------------------------------------------

    public function test_divi_passthrough_when_no_shortcodes(): void
    {
        $content = 'Plain content with no shortcodes.';
        $result  = bd324_filter_remove_divi_shortcodes_from_content($content, $this->post);
        $this->assertSame($content, $result);
    }

    public function test_divi_removes_opening_shortcode(): void
    {
        $content = '[et_pb_section]Some text[/et_pb_section]';
        $result  = bd324_filter_remove_divi_shortcodes_from_content($content, $this->post);
        $this->assertStringNotContainsString('[et_pb_section]', $result);
        $this->assertStringContainsString('Some text', $result);
    }

    public function test_divi_removes_shortcode_with_attributes(): void
    {
        $content = '[et_pb_section fb_built="1" _builder_version="4.9"]Content[/et_pb_section]';
        $result  = bd324_filter_remove_divi_shortcodes_from_content($content, $this->post);
        $this->assertStringNotContainsString('[et_pb_section', $result);
    }

    /**
     * Known behaviour: the regex /\[et_pb.*?\]/ only strips opening tags.
     * Closing tags like [/et_pb_section] are left in place.
     */
    public function test_divi_leaves_closing_shortcodes_intact(): void
    {
        $content = '[et_pb_section]Text[/et_pb_section]';
        $result  = bd324_filter_remove_divi_shortcodes_from_content($content, $this->post);
        $this->assertStringContainsString('[/et_pb_section]', $result);
    }

    public function test_divi_non_string_returned_as_is(): void
    {
        $input  = ['not', 'a', 'string'];
        $result = bd324_filter_remove_divi_shortcodes_from_content($input, $this->post);
        $this->assertSame($input, $result);
    }

    // -------------------------------------------------------
    // bd324_filter_truncate_content
    // Uses default ALGOLIA_MAX_RECORD_LENGTH = 10000, limit = 8000
    // -------------------------------------------------------

    public function test_truncate_short_content_unchanged(): void
    {
        $content = str_repeat('a', 100);
        $result  = bd324_filter_truncate_content($content, $this->post);
        $this->assertSame($content, $result);
    }

    public function test_truncate_content_at_limit_unchanged(): void
    {
        $content = str_repeat('a', 8000);
        $result  = bd324_filter_truncate_content($content, $this->post);
        $this->assertSame($content, $result);
    }

    public function test_truncate_content_over_limit_is_cut(): void
    {
        $content = str_repeat('a', 9000);
        $result  = bd324_filter_truncate_content($content, $this->post);
        $this->assertSame(8000, strlen($result));
    }

    public function test_truncate_preserves_start_of_content(): void
    {
        $content = 'KEEP' . str_repeat('x', 9000);
        $result  = bd324_filter_truncate_content($content, $this->post);
        $this->assertStringStartsWith('KEEP', $result);
    }

    public function test_truncate_non_string_returned_as_is(): void
    {
        $input  = ['not', 'a', 'string'];
        $result = bd324_filter_truncate_content($input, $this->post);
        $this->assertSame($input, $result);
    }
}
