<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}
error_log(print_r(BD616__PLUGIN_DIR, true));

require_once BD616__PLUGIN_DIR . '/inc/algolia/updates/get_args_for_query.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/updates/get_posts_for_update.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/updates/update_algolia_record.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/updates/update_global_index.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/updates/update_algolia_index.php';