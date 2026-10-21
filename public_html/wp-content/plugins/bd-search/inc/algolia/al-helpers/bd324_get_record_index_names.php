<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if (!function_exists('bd324_get_post_language_code')):
/**
 * Get the WPML language code of a post
 *
 * @param int $post_id
 * @return string Language code, or '' if WPML is not active or the post has no language
 */
function bd324_get_post_language_code($post_id)
{
   if (apply_filters('wpml_default_language', null) === null) {
      return '';
   }

   $details = apply_filters('wpml_post_language_details', null, $post_id);

   if (!is_array($details) || empty($details['language_code'])) {
      return '';
   }

   return (string) $details['language_code'];
}
endif;

if (!function_exists('bd324_get_post_index_language')):
/**
 * Get the index language suffix for a post
 *
 * Matches the full-index convention: the default language has no suffix
 * (`wp algolia update`), other languages do (`wp algolia update --lang=chs`).
 *
 * @param int $post_id
 * @return string Language code, or '' for the default language / no WPML
 */
function bd324_get_post_index_language($post_id)
{
   $code = bd324_get_post_language_code($post_id);

   if ($code === '' || $code === apply_filters('wpml_default_language', null)) {
      return '';
   }

   return $code;
}
endif;

if (!function_exists('bd324_get_record_index_names')):
/**
 * Get the full names of every index a post's record belongs in
 *
 * Index names come from `bd324_filter_index_names`, then get the table prefix
 * and the post's language suffix. A name that already ends in the language
 * suffix (e.g. a child plugin returning 'global_chs') is not suffixed twice.
 *
 * @param int    $post_id
 * @param string $post_type
 * @return string[] Full index names, e.g. ['wp_global_chs', 'wp_team_chs']
 */
function bd324_get_record_index_names($post_id, $post_type)
{
   $index_names = apply_filters(
      'bd324_filter_index_names',
      array('global'), // Default
      $post_type,
      $post_id
   );

   $language = bd324_get_post_index_language($post_id);
   $suffix = $language !== '' ? '_' . $language : '';

   $full_names = [];
   foreach ((array) $index_names as $name) {
      if ($suffix !== '' && substr($name, -strlen($suffix)) !== $suffix) {
         $name .= $suffix;
      }
      $full_names[] = apply_filters('bd324_filter_index_name', $name);
   }

   return array_values(array_unique($full_names));
}
endif;
