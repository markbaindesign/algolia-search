<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/* Set config for indices */
function algolia_index_config($index, $algolia_full_index_name)
{
   $forwardToReplicas = true;

   $searchableAttributes = apply_filters(
      'bd324_filter_algolia_index_config_searchableAttributes_' . str_replace('-', '_', $algolia_full_index_name),
      [
         'title',
         'excerpt',
         'content',
      ],
      $index
   );

   $attributesForFaceting = apply_filters(
      'bd324_filter_algolia_index_config_attributesForFaceting_' . str_replace('-', '_', $algolia_full_index_name),
      [],
      $index
   );

   $attributesToSnippet = apply_filters(
      'bd324_filter_algolia_index_config_attributesToSnippet_' . str_replace('-', '_', $algolia_full_index_name),
      [
         'title',
         'content:50'
      ],
      $index
   );

   $ranking = apply_filters(
      'bd324_filter_algolia_index_config_ranking_' . str_replace('-', '_', $algolia_full_index_name),
      [],
      $index
   );

   $index->setSettings(
      [
         'searchableAttributes' => $searchableAttributes,
         'hitsPerPage' => 12,
         'attributesForFaceting' => $attributesForFaceting,
         'attributesToSnippet' => $attributesToSnippet,
      ],
      [
         'forwardToReplicas' => $forwardToReplicas
      ],
      [
         'ranking' => $ranking
      ]
   );
}