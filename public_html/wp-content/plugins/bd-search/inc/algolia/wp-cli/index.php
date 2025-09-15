<?php

require_once BD616__PLUGIN_DIR . '/inc/algolia/wp-cli/update-command.php';

if (!(defined('WP_CLI') && WP_CLI)) {
   return;
}

require_once BD616__PLUGIN_DIR . '/inc/algolia/wp-cli/Algolia_Command.php';
