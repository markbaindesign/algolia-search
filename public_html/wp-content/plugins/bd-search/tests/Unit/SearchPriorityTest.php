<?php

namespace BDSearch\Tests\Unit;

use Brain\Monkey\Functions;

/**
 * Tests for algolia_add_search_priority_to_record.
 *
 * @see inc/algolia/al-records/algolia_add_search_priority_to_record.php
 */
class SearchPriorityTest extends BaseTestCase
{
    public function test_adds_numeric_priority_to_record(): void
    {
        Functions\expect('get_post_meta')
            ->once()->with(42, 'search_priority', true)
            ->andReturn('5');

        $result = algolia_add_search_priority_to_record([], 42);
        $this->assertSame(5, $result['search_priority']);
    }

    public function test_priority_cast_to_int(): void
    {
        Functions\expect('get_post_meta')->andReturn('10');

        $result = algolia_add_search_priority_to_record([], 1);
        $this->assertIsInt($result['search_priority']);
    }

    public function test_zero_priority_added_to_record(): void
    {
        Functions\expect('get_post_meta')->andReturn('0');

        $result = algolia_add_search_priority_to_record([], 1);
        $this->assertArrayHasKey('search_priority', $result);
        $this->assertSame(0, $result['search_priority']);
    }

    public function test_existing_record_fields_preserved(): void
    {
        Functions\expect('get_post_meta')->andReturn('3');

        $record = ['title' => 'Hello', 'objectID' => 'post_1'];
        $result = algolia_add_search_priority_to_record($record, 1);

        $this->assertSame('Hello', $result['title']);
        $this->assertSame('post_1', $result['objectID']);
        $this->assertSame(3, $result['search_priority']);
    }

    /**
     * intval converts any non-numeric string to 0, and is_numeric(0) is true,
     * so an empty or non-numeric meta value results in priority = 0 being added.
     * This documents the current behaviour — if the intent is to skip empty
     * values, the guard should check the raw meta string before intval().
     */
    public function test_empty_meta_results_in_priority_zero(): void
    {
        Functions\expect('get_post_meta')->andReturn('');

        $result = algolia_add_search_priority_to_record([], 1);
        $this->assertArrayHasKey('search_priority', $result);
        $this->assertSame(0, $result['search_priority']);
    }
}
