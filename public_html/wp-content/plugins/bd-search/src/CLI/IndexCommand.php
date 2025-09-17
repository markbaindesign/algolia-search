<?php

namespace BD324\Search\CLI;

class IndexCommand
{
    /**
     * Update the Algolia index
     */
    public function update($args, $assoc_args)
    {
        $output = [];
        $algolia_index_name = isset($assoc_args['index']) ? $assoc_args['index'] : 'global';
        $post_ids = isset($assoc_args['post_ids']) ? explode(',', $assoc_args['post_ids']) : [];
        $verbose = isset($assoc_args['verbose']) ? filter_var($assoc_args['verbose'], FILTER_VALIDATE_BOOLEAN) : null;
        $algolia_index_language = isset($assoc_args['lang']) ? $assoc_args['lang'] : null;

        // Update specific post IDs
        if (!empty($post_ids)) {
            $updater = new \BD324\Search\Algolia\Updater();
            foreach ($post_ids as $post_id) {
                $output[] = $updater->update_record($post_id, \WP_Post::get_instance($post_id));
            }
        }
        // Update index
        if (!empty($algolia_index_name)) {
            $updater = new \BD324\Search\Algolia\Updater();
            $output = $updater->update_index(
                $algolia_index_name,
                $algolia_index_language
            );
        }

        \WP_CLI::success("Index update command completed.");
        \WP_CLI::success("Details: " . print_r($output, true));

        return $output;
    }

    /**
     * Provide information about the environment
     */
    public function check_env()
    {
        /**
         * Check the server, app ID, env
         */
        // DB Name
        if (defined('DB_NAME')) {
            \WP_CLI::success("DB Name : " . DB_NAME);
        } else {
            \WP_CLI::warning("DB Name not defined");
        }

        // WP ENV
        if (defined('WP_ENVIRONMENT_TYPE')) {
            \WP_CLI::success("WP Env : " . WP_ENVIRONMENT_TYPE);
        } else {
            \WP_CLI::warning('WP Env not defined');
        }

        // APP ID
        if (defined('ALGOLIA_APPLICATION_ID')) {
            \WP_CLI::success("App ID : " . ALGOLIA_APPLICATION_ID);
        } else {
            \WP_CLI::warning('App ID not defined');
        }
    }

}
