<?php

if (!defined('ABSPATH')) {
    exit('Invalid request, dude.');
}

require_once BD616__PLUGIN_DIR . '/vendor/autoload.php';

$includes = [
   ...array_map(
       fn ($path) => BD616__PLUGIN_DIR . $path,
       [
         '/inc/algolia/index.php',
         '/inc/scripts/index.php',
         '/inc/shortcodes/index.php',
         '/inc/styles/index.php',
         '/inc/wpml.php',
         '/inc/templates/index.php',
      ]
   )
];

foreach ($includes as $file) {
    require_once $file;
}
