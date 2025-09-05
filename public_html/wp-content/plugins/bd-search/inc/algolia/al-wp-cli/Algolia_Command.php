<?php // 

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

class Algolia_Command
{
   /**
    * Update the Algolia index
    */
   public function update($args, $assoc_args)
   {
      $index = isset($assoc_args['index']) ? $assoc_args['index'] : 'global';
      $post_ids = isset($assoc_args['post_ids']) ? explode(',', $assoc_args['post_ids']) : [];
      $verbose = isset($assoc_args['verbose']) ? filter_var($assoc_args['verbose'], FILTER_VALIDATE_BOOLEAN) : null;
      $lang = isset($assoc_args['lang']) ? $assoc_args['lang'] : null;
      
      bd324_algolia_update_command($index, $post_ids, $verbose, $lang);
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
         WP_CLI::success("DB Name : " . DB_NAME);
      } else {
         WP_CLI::warning("DB Name not defined");
      }

      // WP ENV
      if (defined('WP_ENVIRONMENT_TYPE')) {
         WP_CLI::success("WP Env : " . WP_ENVIRONMENT_TYPE);
      } else {
         WP_CLI::warning('WP Env not defined');
      }

      // APP ID
      if (defined('ALGOLIA_APPLICATION_ID')) {
         WP_CLI::success("App ID : " . ALGOLIA_APPLICATION_ID);
      } else {
         WP_CLI::warning('App ID not defined');
      }
   }

}

WP_CLI::add_command('algolia', 'Algolia_Command');
