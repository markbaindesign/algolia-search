<?php

namespace BD324\Search;

use Algolia\AlgoliaSearch\SearchClient;
use BD324\Search\Admin\Page;
use BD324\Search\Admin\Metaboxes;
use BD324\Search\Frontend\Assets\Styles;
use BD324\Search\CLI\IndexCommand;
use BD324\Search\Helpers\ArrayHelpers;
use BD324\Search\Helpers\StringHelpers;
use BD324\Search\Filters\IndexFilter;
use BD324\Search\Filters\RecordFilter;
use BD324\Search\Filters\SearchFilter;
use BD324\Search\Frontend\Assets\Scripts\ScriptManager;

class Loader
{
    public function register()
    {
        // Admin
        $adminPage = new Page();
        $adminPage->register();

        $adminMetaboxes = new Metaboxes();
        $adminMetaboxes->register();

        // Scripts (managed by ScriptManager)
        (new ScriptManager())->register();

        // Algolia Client
        Algolia\Client::get_instance();

        // Frontend: Styles
        $frontendStyles = new Styles();
        $frontendStyles->register();

        // Filters
        new IndexFilter();
        new RecordFilter();
        new SearchFilter();

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

            try {
                $updater->update_record($post_id, $post);
            } catch (\Exception $e) {
                error_log(
                    sprintf(
                        '[Algolia] Error updating record for post ID %d: %s',
                        $post_id,
                        $e->getMessage()
                    )
                );
            }

        }, 10, 2);
    }
}
