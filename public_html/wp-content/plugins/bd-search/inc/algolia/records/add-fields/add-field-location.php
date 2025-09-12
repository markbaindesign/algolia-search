<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if (!function_exists('algolia_add_location_to_record')):
   /**
    * Add Content Length field to Record
    * @since               1.6.0
    * @param   array       $record
    * @param   WP_Post     $post
    */
   function algolia_add_location_to_record($record, $post_id)
   {
      // ==================
      //    GeoLocation
      // ==================
      $longitude = get_field('longitude', $post_id);
      if ($longitude) {
         $record['longitude'] = floatval($longitude);
      } else {
         $record['longitude'] = floatval('0');
      }

      $latitude = get_field('latitude', $post_id);
      if ($latitude) {
         $record['latitude'] = floatval($latitude);
      } else {
         $record['latitude'] = floatval('0');
      }

      $country = get_field('country', $post_id);
      if ($country) {
         $record['country'] = $country;
      }

      return $record;
   }
endif;
add_filter('add_location_to_record', 'algolia_add_location_to_record', 10, 2);
