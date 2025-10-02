<?php

//

if (!defined('ABSPATH')) {
    die('Invalid request, dude!');
}

/**
 * Update Algolia index
 *
 * functionDescription
 * @param $functionParam       functionDescription
 */
if (!function_exists('bd324_algolia_update_index')):
    function bd324_algolia_update_index(
        $algolia_index_name,
        $algolia_index_language,
        $post_types,
        $post_ids
    ) {

        global $algolia;
        $update_index = []; // To record the number of records indexed
        $update_index['count'] = 0;

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [UPDATE_INDEX] Starting index update - Index: '{$algolia_index_name}', Language: '{$algolia_index_language}'");
            error_log("BD324 DEBUG: [UPDATE_INDEX] Post types: " . implode(', ', (array) $post_types));
            if (!empty($post_ids)) {
                error_log("BD324 DEBUG: [UPDATE_INDEX] Specific post IDs: " . implode(', ', (array) $post_ids));
            }
        }

        /**
         * Get full index name
         * Includes table prefix & language parameter
         */
        $algolia_full_index_name = apply_filters(
            'bd324_get_full_index_name',
            $algolia_index_name,
            $algolia_index_language,
        );

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [UPDATE_INDEX] Full index name: '{$algolia_full_index_name}'");
        }

        $algoliaIndex = $algolia->initIndex($algolia_full_index_name);

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [UPDATE_INDEX] Clearing existing objects from index");
        }

        $algoliaIndex->clearObjects()->wait();

        $paged = 1;
        $count = 0;
        $skipped_posts = 0;
        $oversized_records = 0;

        if (apply_filters('wpml_default_language', null) !== null) :
            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [UPDATE_INDEX] Switching to language: '{$algolia_index_language}'");
            }
            // Switch language
            do_action('wpml_switch_language', $algolia_index_language);
        endif;

        do {

            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [UPDATE_INDEX] Processing page {$paged}");
            }

            // Get query args
            if (function_exists('bd324_get_args_for_query')):
                $args = bd324_get_args_for_query(
                    $algolia_index_name,
                    $algolia_index_language,
                    $post_types,
                    $post_ids,
                    $paged
                );
            endif;

            $posts = new WP_Query($args);

            if (!$posts->have_posts()) {
                if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                    error_log("BD324 DEBUG: [UPDATE_INDEX] No more posts found on page {$paged}, ending loop");
                }
                break;
            }

            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [UPDATE_INDEX] Found " . count($posts->posts) . " posts on page {$paged}");
            }

            $records = [];

            /* Add posts to records */
            foreach ($posts->posts as $post) {

                $record = [];

                if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                    error_log("BD324 DEBUG: [UPDATE_INDEX] Processing post #{$post->ID} ({$post->post_type}): '{$post->post_title}'");
                }

                // Check post is allowed in the index
                if (!BD616__is_post_allowed($post->ID, get_post_type($post->ID), $algolia_index_name)) {
                    $skipped_posts++;
                    if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                        error_log("BD324 DEBUG: [UPDATE_INDEX] Post #{$post->ID} not allowed in index - skipping");
                    }
                    continue;
                }

                // Convert post data to Algolia record
                $record = bd324_convert_post_data($post);

                if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                    $record_size = bd324_calculate_record_size($record);
                    error_log("BD324 DEBUG: [UPDATE_INDEX] Post #{$post->ID} converted to record - Size: {$record_size} bytes, Fields: " . implode(', ', array_keys($record)));
                }

                /* Check record size does not exceed Algolia Max Record Size */
                if (!BD616_check_record_size($record, $post->ID)) {
                    $oversized_records++;
                    if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                        error_log("BD324 DEBUG: [UPDATE_INDEX] Post #{$post->ID} record exceeds size limit - skipping");
                    }
                    continue;
                }

                /* Add record to array */
                $records[] = $record;
                $count++;

                if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                    error_log("BD324 DEBUG: [UPDATE_INDEX] Post #{$post->ID} added to records array (total records: {$count})");
                }
            }

            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [UPDATE_INDEX] Page {$paged} processed - Records to index: " . count($records));
            }

            /* Add taxonomies to records */
            $records_before_tax = count($records);
            $records = apply_filters(
                'bd324_filter_add_to_records_tax_terms',
                $records,
                $algolia_index_name,
                $algolia_index_language
            );

            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                $records_after_tax = count($records);
                if ($records_after_tax !== $records_before_tax) {
                    error_log("BD324 DEBUG: [UPDATE_INDEX] Taxonomy filter changed record count: {$records_before_tax} → {$records_after_tax}");
                }
            }

            /* Filter records */
            $records_before_filter = count($records);
            $records = apply_filters(
                'bd324_filter_records_before_indexing',
                $records,
                $algolia_index_name,
                $algolia_index_language
            );

            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                $records_after_filter = count($records);
                if ($records_after_filter !== $records_before_filter) {
                    error_log("BD324 DEBUG: [UPDATE_INDEX] Pre-indexing filter changed record count: {$records_before_filter} → {$records_after_filter}");
                }
            }

            $records = mb_convert_encoding($records, 'UTF-8', 'UTF-8');

            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [UPDATE_INDEX] Saving " . count($records) . " records to Algolia index");
            }

            /* Save records to the index */
            $algoliaIndex->saveObjects($records);

            $paged++;
        } while (true);

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [UPDATE_INDEX] Setting index configuration");
        }

        // Set settings
        algolia_index_config($algoliaIndex, $algolia_full_index_name);

        $update_index['count'] = $count;
        $update_index['skipped_posts'] = $skipped_posts;
        $update_index['oversized_records'] = $oversized_records;
        $update_index['algolia_full_index_name'] = $algolia_full_index_name;

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [UPDATE_INDEX] Index update completed:");
            error_log("BD324 DEBUG: [UPDATE_INDEX] - Total records indexed: {$count}");
            error_log("BD324 DEBUG: [UPDATE_INDEX] - Posts skipped (not allowed): {$skipped_posts}");
            error_log("BD324 DEBUG: [UPDATE_INDEX] - Records skipped (oversized): {$oversized_records}");
            error_log("BD324 DEBUG: [UPDATE_INDEX] - Full index name: '{$algolia_full_index_name}'");
        }

        return $update_index;
    }
endif;
