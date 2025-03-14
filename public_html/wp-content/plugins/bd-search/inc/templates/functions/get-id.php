<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Get Algolia Template Part ID
 *
 * Returns a unique ID for an Algolia template part, based
 * on the template part name, index name, and template name.
 * 
 * @param $template_part_name   The name of the template part
 * @param $index_name           The name of the index
 * @param $template_name        The name of the template
 * @return $template_id         The unique ID
 * 
 */

function bd324_get_algolia_template_part_id($template_part_name, $index_name = null, $template_name = null)
{
   $template_id = 'algolia-';
   $template_id .= $template_part_name;

   if ($index_name) {
      $template_id .= '--' . $index_name;
   }

   if ($template_name) {
      $template_id .= '--' . $template_name;
   }

   return $template_id;
}