<?php

if (!defined('ABSPATH')) {
    exit('Invalid request, dude.');
}

$includes = [
   ...array_map(
       fn ($path) => BD616__PLUGIN_DIR . $path,
       [
         '/inc/algolia/index.php',
         '/inc/shortcodes/index.php',
         '/inc/templates/index.php',
      ]
   )
];

foreach ($includes as $file) {
    require_once $file;
}
