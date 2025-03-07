<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Convert ACF Cover data to Algolia record
 */
function algolia_acf_cover_field_values_to_record($record, $post_id)
{
   // Cover Text
   $cover_text = get_field('cover_text', $post_id);
   if ($cover_text) {
      $record['cover_text'] = $cover_text;
   }

   // Cover Quote
   $cover_quote = get_field('quote_text', $post_id);
   if ($cover_quote) {
      $record['cover_quote'] = $cover_quote;
   }

   return $record;
}
add_filter('acf_cover_field_values_to_record', 'algolia_acf_cover_field_values_to_record', 10, 2);