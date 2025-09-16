<?php

namespace BD324\Search\Algolia;

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

    public function update_index()
    {
        // To be implemented
    }
}
