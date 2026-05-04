<?php

namespace BDSearch\Tests\Unit;

/**
 * Tests for bd324_handle_big_data_in_value.
 *
 * Enforces the 50,000-byte field size limit before sending data to Algolia.
 *
 * @see inc/algolia/al-helpers/bd324_handle_big_data_in_value.php
 */
class FieldSizeTest extends BaseTestCase
{
    public function test_returns_null_when_value_key_missing(): void
    {
        $result = bd324_handle_big_data_in_value(['name' => 'my_field']);
        $this->assertNull($result);
    }

    public function test_returns_null_when_name_key_missing(): void
    {
        $result = bd324_handle_big_data_in_value(['value' => 'hello']);
        $this->assertNull($result);
    }

    public function test_returns_null_when_value_is_array(): void
    {
        $result = bd324_handle_big_data_in_value([
            'name'  => 'my_field',
            'value' => ['not', 'a', 'string'],
        ]);
        $this->assertNull($result);
    }

    public function test_returns_null_when_value_exceeds_50000_bytes(): void
    {
        $result = bd324_handle_big_data_in_value([
            'name'  => 'my_field',
            'value' => str_repeat('a', 50001),
        ]);
        $this->assertNull($result);
    }

    public function test_returns_null_at_exact_50001_bytes(): void
    {
        $result = bd324_handle_big_data_in_value([
            'name'  => 'body',
            'value' => str_repeat('x', 50001),
        ]);
        $this->assertNull($result);
    }

    public function test_returns_field_array_when_value_within_limit(): void
    {
        $result = bd324_handle_big_data_in_value([
            'name'  => 'title',
            'value' => 'Hello world',
        ]);
        $this->assertSame(['title' => 'Hello world'], $result);
    }

    public function test_strips_html_tags_from_returned_value(): void
    {
        $result = bd324_handle_big_data_in_value([
            'name'  => 'excerpt',
            'value' => '<p>Some <strong>content</strong> here</p>',
        ]);
        $this->assertSame(['excerpt' => 'Some content here'], $result);
    }

    public function test_accepts_value_at_exactly_50000_bytes(): void
    {
        $result = bd324_handle_big_data_in_value([
            'name'  => 'body',
            'value' => str_repeat('z', 50000),
        ]);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('body', $result);
    }
}
