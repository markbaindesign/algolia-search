<?php

namespace BD324\Search;

use BD324\Search\Admin\Page;
use BD324\Search\Admin\Metaboxes;
use BD324\Search\Frontend\Assets;
use BD324\Search\CLI\IndexCommand;

class Loader
{
    public function register()
    {
        // Admin
        $adminPage = new Page();
        $adminPage->register();

        $adminMetaboxes = new Metaboxes();
        $adminMetaboxes->register();

        // Frontend
        $frontendAssets = new Assets();
        $frontendAssets->register();
        // CLI
        if (defined('WP_CLI') && WP_CLI) {
            // Register the CLI command
            \WP_CLI::add_command('algolia', \BD324\Search\CLI\IndexCommand::class);
        }

        // Algolia: run update_record on post save
        /**
         * Updates Algolia record when a post is saved.
         *
         * @param int     $post_id The ID of the post being saved.
         * @param WP_Post $post    The post object.
         */
        add_action('save_post', function ($post_id, $post) {
            // Only run for non-autosave, non-revision
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            if (wp_is_post_revision($post_id)) {
                return;
            }

            // Only run for WP_Post objects
            if (!($post instanceof \WP_Post)) {
                $post = get_post($post_id);
            }
            if (!($post instanceof \WP_Post)) {
                return;
            }

            static $updater = null;
            if ($updater === null) {
                $updater = new \BD324\Search\Algolia\Updater();
            }
            $updater->update_record($post_id, $post);
        }, 10, 2);
    }
}
