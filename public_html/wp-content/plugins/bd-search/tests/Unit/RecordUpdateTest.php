<?php

namespace BDSearch\Tests\Unit;

use Brain\Monkey\Functions;
use Mockery;

/**
 * Tests for single-record updates: save_post and `wp algolia update --post_ids`.
 *
 * A record must reach every index it is routed to, in its own language, and
 * the --post_ids path must never fall through to a full re-index (which
 * clears the index first).
 *
 * @see inc/algolia/al-helpers/bd324_get_record_index_names.php
 * @see inc/algolia/updates/update_algolia_record.php
 * @see inc/algolia/al-wp-cli/al-update-command.php
 */
class RecordUpdateTest extends BaseTestCase
{
    /** @var array<string, callable> Filter callbacks, keyed by hook name */
    private array $filters = [];

    /** @var array<string, string[]> Calls made to each mocked Algolia index */
    private array $calls = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->filters = [
            'bd324_filter_index_name' => fn($name) => 'wp_' . $name,
        ];
        Functions\when('apply_filters')->alias(function ($hook, $value, ...$args) {
            return isset($this->filters[$hook]) ? ($this->filters[$hook])($value, ...$args) : $value;
        });

        Functions\when('get_post_type')->justReturn('post');
        Functions\when('get_post_status')->justReturn('publish');
        Functions\when('wp_is_post_revision')->justReturn(false);
        Functions\when('wp_is_post_autosave')->justReturn(false);
        Functions\when('bd324_convert_post_data')->alias(fn($post) => ['objectID' => $post->ID]);

        $this->calls = [];
        global $algolia;
        $algolia = Mockery::mock();
        $algolia->shouldReceive('initIndex')->andReturnUsing(function ($name) {
            $index = Mockery::mock();
            $index->shouldReceive('saveObject')->andReturnUsing(function () use ($name) {
                $this->calls[$name][] = 'save';
            });
            $index->shouldReceive('deleteObject')->andReturnUsing(function () use ($name) {
                $this->calls[$name][] = 'delete';
            });
            return $index;
        });
    }

    private function useWpml(string $default, array $post_languages): void
    {
        $this->filters['wpml_default_language'] = fn() => $default;
        $this->filters['wpml_post_language_details'] = fn($value, $post_id) =>
            isset($post_languages[$post_id]) ? ['language_code' => $post_languages[$post_id]] : null;
    }

    // -------------------------------------------------------
    // bd324_parse_post_ids
    // -------------------------------------------------------

    public function test_parse_post_ids_from_string(): void
    {
        $this->assertSame([1, 2, 3], bd324_parse_post_ids(' 1, 2,3 '));
    }

    public function test_parse_post_ids_from_array(): void
    {
        $this->assertSame([25131, 7], bd324_parse_post_ids(['25131', ' 7']));
    }

    public function test_parse_post_ids_drops_invalid_and_duplicate_values(): void
    {
        $this->assertSame([4, 9], bd324_parse_post_ids('4,abc,,-2,0,4,1.5,9'));
    }

    public function test_parse_post_ids_rejects_empty_and_flag_values(): void
    {
        $this->assertSame([], bd324_parse_post_ids(''));
        $this->assertSame([], bd324_parse_post_ids(true));
        $this->assertSame([], bd324_parse_post_ids(null));
    }

    // -------------------------------------------------------
    // bd324_get_record_index_names
    // -------------------------------------------------------

    public function test_index_names_default_to_global(): void
    {
        $this->assertSame(['wp_global'], bd324_get_record_index_names(1, 'post'));
    }

    public function test_index_names_get_language_suffix(): void
    {
        $this->useWpml('en', [1 => 'chs']);
        $this->filters['bd324_filter_index_names'] = fn($names) => ['global', 'team'];

        $this->assertSame(['wp_global_chs', 'wp_team_chs'], bd324_get_record_index_names(1, 'team'));
    }

    public function test_default_language_has_no_suffix(): void
    {
        $this->useWpml('en', [1 => 'en']);

        $this->assertSame(['wp_global'], bd324_get_record_index_names(1, 'post'));
    }

    public function test_language_suffix_is_not_doubled(): void
    {
        $this->useWpml('en', [1 => 'chs']);
        $this->filters['bd324_filter_index_names'] = fn($names) => ['global_chs'];

        $this->assertSame(['wp_global_chs'], bd324_get_record_index_names(1, 'post'));
    }

    public function test_index_names_filter_receives_post_id(): void
    {
        $received = null;
        $this->filters['bd324_filter_index_names'] = function ($names, $post_type, $post_id) use (&$received) {
            $received = [$post_type, $post_id];
            return $names;
        };

        bd324_get_record_index_names(42, 'event');
        $this->assertSame(['event', 42], $received);
    }

    // -------------------------------------------------------
    // bd324_update_algolia_record
    // -------------------------------------------------------

    public function test_record_is_saved_to_every_routed_index(): void
    {
        $this->filters['bd324_filter_index_names'] = fn($names) => ['global', 'team'];

        $result = bd324_update_algolia_record(1, new \WP_Post(['ID' => 1]));

        $this->assertTrue($result);
        $this->assertSame(['wp_global' => ['save'], 'wp_team' => ['save']], $this->calls);
    }

    public function test_unpublished_record_is_removed_from_every_routed_index(): void
    {
        Functions\when('get_post_status')->justReturn('draft');
        $this->filters['bd324_filter_index_names'] = fn($names) => ['global', 'team'];

        $result = bd324_update_algolia_record(1, new \WP_Post(['ID' => 1]));

        $this->assertFalse($result);
        $this->assertSame(['wp_global' => ['delete'], 'wp_team' => ['delete']], $this->calls);
    }

    // -------------------------------------------------------
    // bd324_algolia_update_command with post IDs
    // -------------------------------------------------------

    public function test_post_ids_update_only_those_records(): void
    {
        Functions\expect('bd324_algolia_update_index')->never();
        Functions\when('get_post')->alias(fn($id) => new \WP_Post(['ID' => $id]));
        $this->useWpml('en', [25131 => 'chs']);

        $count = bd324_algolia_update_command('global', ['25131'], null, 'chs');

        $this->assertSame(1, $count);
        $this->assertSame(['wp_global_chs' => ['save']], $this->calls);
    }

    public function test_post_ids_string_is_accepted(): void
    {
        Functions\expect('bd324_algolia_update_index')->never();
        Functions\when('get_post')->alias(fn($id) => new \WP_Post(['ID' => $id]));

        $this->assertSame(2, bd324_algolia_update_command('global', '1,2'));
    }

    public function test_lang_skips_posts_in_other_languages(): void
    {
        Functions\expect('bd324_algolia_update_index')->never();
        Functions\when('get_post')->alias(fn($id) => new \WP_Post(['ID' => $id]));
        $this->useWpml('en', [1 => 'chs', 2 => 'cht']);

        $count = bd324_algolia_update_command('global', '1,2', null, 'chs');

        $this->assertSame(1, $count);
        $this->assertSame(['wp_global_chs' => ['save']], $this->calls);
    }

    public function test_missing_posts_are_skipped(): void
    {
        Functions\expect('bd324_algolia_update_index')->never();
        Functions\when('get_post')->justReturn(null);

        $this->assertSame(0, bd324_algolia_update_command('global', '99'));
        $this->assertSame([], $this->calls);
    }

    /**
     * @dataProvider invalid_post_ids
     */
    public function test_invalid_post_ids_never_reindex($post_ids): void
    {
        Functions\expect('bd324_algolia_update_index')->never();

        $this->assertSame(0, bd324_algolia_update_command('global', $post_ids));
        $this->assertSame([], $this->calls);
    }

    public function invalid_post_ids(): array
    {
        return [
            'empty value (--post_ids=)' => [''],
            'bare flag (--post_ids)'    => [true],
            'non-numeric'               => ['abc'],
        ];
    }

    public function test_no_post_ids_runs_full_index(): void
    {
        Functions\when('bd324_get_post_types_for_index')->justReturn(['post']);
        Functions\expect('bd324_algolia_update_index')->once()->andReturn(['count' => 0]);

        bd324_algolia_update_command('global', [], null, null);
        $this->addToAssertionCount(1);
    }
}
