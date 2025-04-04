<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

require_once BD616__PLUGIN_DIR . '/inc/algolia/al-updates/bd324_update_algolia_record.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-updates/algolia_update_global_index.php';
require_once 'get_posts_for_update.php';
require_once 'get_args_for_query.php';