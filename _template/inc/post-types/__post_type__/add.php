<?php // Record: __post_type__

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

add_filter('__post_type___to_record', '__CONST____post_type___to_record', 10, 2);

function __CONST____post_type___to_record($record, WP_Post $post)
{
    $post_id = $post->ID;
    $record = apply_filters('add_permalink_to_record', $record, $post_id);
    $record = apply_filters('add_wordpress_post_type_to_record', $record, $post_id);
    $record = apply_filters('add_content_to_record', $record, $post);
    $record = apply_filters('add_acf_content_to_record', $record, $post);
    $record = apply_filters('add_featured_to_record', $record, $post_id);
    $record = apply_filters('add_search_priority_to_record', $record, $post_id);
    // Add taxonomies (stored as objects: {display, name} — use 'my_taxonomy.display' in attributesForFaceting):
    // $record = apply_filters('add_tax_values_to_record', $record, $post_id, 'my_taxonomy');
    // $record = apply_filters('add_hierarchical_tax_values_to_record', $record, $post_id, 'my_tax');
    // Note: if a post meta field shares the same name as a taxonomy, use a distinct key for the meta
    // (e.g. 'my_taxonomy_note') to avoid clobbering the taxonomy object in the record.

    // ACF date field → Unix timestamp (required for Algolia date filtering/sorting):
    // $date = get_field('event_date', $post_id);  // returns 'YYYYMMDD' from ACF date picker
    // if ($date) {
    //     $record['event_date_timestamp'] = (int) (new DateTime($date))->format('U');
    // }

    // Geolocation (requires lat/lng ACF fields on the post):
    // $lat = get_field('location_lat', $post_id);
    // $lng = get_field('location_lng', $post_id);
    // if ($lat && $lng) {
    //     $record['_geoloc'] = ['lat' => (float) $lat, 'lng' => (float) $lng];
    // }

    return $record;
}
