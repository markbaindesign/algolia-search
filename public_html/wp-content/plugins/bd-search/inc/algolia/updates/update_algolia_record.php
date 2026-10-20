<?php

if (!defined('ABSPATH')) {
    die('Invalid request, dude!');
}

/**
 * Update Algolia Record
 *
 * Updates single record in Algolia (all fields, all post types).
 *
 * @param $post_id
 * @param WP_Post
 * @param $post
 *
 */
function bd324_update_algolia_record($post_id, WP_Post $post)
{
    global $algolia;

    if (!$post) {
        $post = get_post();
    }
    $post_type = get_post_type($post_id);
    $post_status = get_post_status($post_id);

    /**
     * Don't update record if this is a revision or autosave
     */
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    // Convert post data to Algolia record
    $record = bd324_convert_post_data($post);

    // Full index names (prefix + language suffix), from `bd324_filter_index_names`
    $index_names = bd324_get_record_index_names($post_id, $post_type);

    // Remove any drafts or password-protected posts from the index
    $remove = 'publish' !== $post_status || !empty($post->post_password);

    /**
     * Update the record in every index it belongs in
     */
    foreach ($index_names as $nameFull) {

        $index = $algolia->initIndex($nameFull);

        if ($remove) {
            $index->deleteObject($record['objectID']);
        } else {
            $index->saveObject($record);
        }
    }

    return !$remove && !empty($index_names);
}
add_action('save_post', 'bd324_update_algolia_record', 10, 3);
