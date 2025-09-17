<?php

namespace BD324\Search\Algolia;

use Algolia\AlgoliaSearch\SearchClient;

class Updater
{
    public function update_record($post_id, \WP_Post $post)
    {
        global $algolia;

        if (!$post) {
            $post = get_post();
        }
        $post_type = get_post_type($post_id);
        $post_status = get_post_status($post_id);
        $algolia_index_language = '';

        /**
         * Don't update record if this is a revision or autosave
         */
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }

        // Convert post data to Algolia record
        $record = bd324_convert_post_data($post);

        /* Check record size does not exceed Algolia Max Record Size */
        if (!BD616_check_record_size($record, $post->ID)) {
            return false;
        };

        // Get an array of index names
        // ***************************
        // Add filter to include project-specific
        // index names.
        $index_names = apply_filters(
            'bd324_filter_index_names_for_post_type_' . str_replace('-', '_', $post_type),
            array('global'), // Default
            $post_type
        );

        if (empty($index_names) || !is_array($index_names)) {
            return false;
        }

        /**
         * Loop over the array of indices
         */
        foreach ($index_names as $name) {
            /**
             * Get full index name
             * Includes table prefix & language parameter
             */
            $algolia_full_index_name = apply_filters(
                'bd324_get_full_index_name',
                $name,
                $algolia_index_language,
            );
            error_log('IndexName (raw): ' . var_export($algolia_full_index_name, true));
            error_log('IndexName (hex): ' . bin2hex($algolia_full_index_name));
            $index = $algolia->initIndex($algolia_full_index_name);

            // Remove any drafts or password-protected posts from the index
            if ('publish' !== $post_status || !empty($post->post_password)) {
                $index->deleteObject($record['objectID']);
                return false;
            } else {
                $index->saveObject($record);
                return true;
            }
        }
    }

    public function update_index(
        $algolia_index_name,
        $algolia_index_language
    ) {

        global $algolia;
        $output = []; // To record the number of records indexed
        $output['count'] = 0;


        /* Get post types */
        $post_types = bd324_get_post_types_for_index($algolia_index_name);

        /**
         * Get full index name
         * Includes table prefix & language parameter
         */
        $algolia_full_index_name = apply_filters(
            'bd324_get_full_index_name',
            $algolia_index_name,
            $algolia_index_language,
        );

        $algoliaIndex = $algolia->initIndex($algolia_full_index_name);
        $algoliaIndex->clearObjects()->wait();

        $paged = 1;
        $count = 0;

        if (apply_filters('wpml_default_language', null) !== null) :
            // Switch language
            do_action('wpml_switch_language', $algolia_index_language);
        endif;

        do {

            // Get query args
            if (function_exists('bd324_get_args_for_query')):
                $args = bd324_get_args_for_query(
                    $algolia_index_name,
                    $algolia_index_language,
                    $post_types,
                    [],
                    $paged
                );
            endif;

            $posts = new \WP_Query($args);

            if (!$posts->have_posts()) {
                break;
            }

            $records = [];

            /* Add posts to records */
            foreach ($posts->posts as $post) {

                $record = [];
                $post_id = $post->ID;

                // Check post is allowed in the index
                if (!BD616__is_post_allowed($post_id, get_post_type($post_id), $algolia_index_name)) {
                    continue;
                }

                // Convert post data to Algolia record
                $record = bd324_convert_post_data($post);

                /* Check record size does not exceed Algolia Max Record Size */
                if (!BD616_check_record_size($record, $post_id)) {
                    continue;
                };


                /* Add record to array */
                $records[] = $record;
                $count++;
            }

            /* Add taxonomies to records */
            $records = apply_filters(
                'bd324_filter_add_to_records_tax_terms',
                $records,
                $algolia_index_name,
                $algolia_index_language
            );

            /* Filter records */
            $records = apply_filters(
                'bd324_filter_records_before_indexing',
                $records,
                $algolia_index_name,
                $algolia_index_language
            );

            $records = mb_convert_encoding($records, 'UTF-8', 'UTF-8');

            /* Save records to the index */
            $algoliaIndex->saveObjects($records);

            $paged++;
        } while (true);

        // Set settings
        $settings = new Settings();
        $settings_result = $settings->index_settings($algoliaIndex, $algolia_full_index_name);

        // Prepare output
        $output['settings'] = $settings_result;
        $output['count'] = $count;
        $output['algolia_full_index_name'] = $algolia_full_index_name;

        return $output;
    }
}
