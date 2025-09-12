<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Convert ACF Location data to Algolia record
 */
function algolia_acf_location_field_values_to_record($record, $post_id, $post_type)
{
   // ==================
   //    GeoLocation
   // ==================
   $location = get_field('location', $post_id);
   if ($location) {
      $dataLat = floatval($location['lat']);
      $dataLng = floatval($location['lng']);
      $country = $location['country'];
   } else {
      $dataLat = floatval('0');
      $dataLng = floatval('0');
      $country = '';
   }

   $record['_geoloc'] = [
      'lat' => $dataLat,
      'lng' => $dataLng,
   ];

   $record['country'] = $country;

   return $record;
}
add_filter('acf_location_field_values_to_record', 'algolia_acf_location_field_values_to_record', 10, 3);