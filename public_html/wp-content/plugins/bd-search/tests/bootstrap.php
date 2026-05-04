<?php

define('ABSPATH', dirname(__DIR__) . '/');

// No-op stubs for WP registration functions called at file-include time.
// Brain Monkey handles the rest per-test.
function add_filter(...$args): bool { return true; }
function add_action(...$args): bool { return true; }

// Minimal WP_Post stub — enough for type-hinted function signatures.
if (!class_exists('WP_Post')) {
    class WP_Post {
        public int    $ID             = 0;
        public string $post_content   = '';
        public string $post_type      = 'post';
        public string $post_status    = 'publish';
        public string $post_password  = '';

        public function __construct(array $data = [])
        {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
    }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Source files under test — included once here so all test classes share them.
$plugin = dirname(__DIR__) . '/inc';

require_once $plugin . '/templates/functions/get-classes.php';
require_once $plugin . '/templates/functions/get-id.php';
require_once $plugin . '/algolia/al-helpers/bd324_handle_big_data_in_value.php';
require_once $plugin . '/algolia/al-records/al-filters/add-filter-remove-divi.php';
require_once $plugin . '/algolia/al-records/al-filters/add-filter-truncate-content.php';
require_once $plugin . '/algolia/al-helpers/bd324_algolia_get_full_index_name.php';
require_once $plugin . '/algolia/al-filters/bd324_add_table_prefix_to_index_name.php';
require_once $plugin . '/algolia/updates/get_args_for_query.php';
require_once $plugin . '/algolia/al-records/algolia_add_search_priority_to_record.php';
