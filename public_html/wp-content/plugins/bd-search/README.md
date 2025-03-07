# BD Search Plugin

## v2.0.0

## Run Composer

* Run Composer to install `/vendor` directory. `composer install`
* This should be done in the Vagrant shell environment.

## Algolia keys

Add all keys to `wp-config.php` as follows:

```
define('ALGOLIA_APPLICATION_ID', 'XXXX');
define('ALGOLIA_SEARCH_API_KEY', 'XXXX');
define('ALGOLIA_API_KEY', 'XXXX');
```

### Algolia keys - multiple environments

```
// VERIFY WHICH ENVIRONMENT THE APP IS RUNNING
if (defined('DB_NAME')) {
   switch (DB_NAME) {
      case 'my_local_db': // Localhost
         define('WP_ENVIRONMENT_TYPE', 'local');
         break;
      case 'my_dev_db': // Dev
         define('WP_ENVIRONMENT_TYPE', 'development');
         break;
      case 'my_staging_db': // Staging
         define('WP_ENVIRONMENT_TYPE', 'staging');
         break;
      case 'my_production_db': // Production
         define('WP_ENVIRONMENT_TYPE', 'production');
         break;
      default: // Default
         define('WP_ENVIRONMENT_TYPE', '');
   }
}

if (defined('WP_ENVIRONMENT_TYPE')) {

   if ('local' == WP_ENVIRONMENT_TYPE) {
      // Local settings

      // Debug settings
      define('WP_DEBUG', true);
      define('SCRIPT_DEBUG', true);
      define('WP_DEBUG_DISPLAY', false);
      define('WP_DEBUG_LOG', true);
      define('WP_DISABLE_FATAL_ERROR_HANDLER', true);

      // Algolia settings
      define('ALGOLIA_APPLICATION_ID', 'XXXX');
      define('ALGOLIA_SEARCH_API_KEY', 'XXXX');
      define('ALGOLIA_API_KEY', 'XXXX');

      // Other settings
      define('OTGS_INSTALLER_SITE_KEY_WPML', 'XXXX');
   } elseif ('development' == WP_ENVIRONMENT_TYPE) {
      // Dev environment settings

      // Debug settings
      define('WP_DEBUG', true);
      define('SCRIPT_DEBUG', true);
      define('WP_DEBUG_DISPLAY', false);
      define('WP_DEBUG_LOG', true);
      define('WP_DISABLE_FATAL_ERROR_HANDLER', true);

      // Other settings
      define('OTGS_INSTALLER_SITE_KEY_WPML', 'XXXX');
   } elseif ('production' == WP_ENVIRONMENT_TYPE) {

      // Production environment settings

      // Debug settings
      define('WP_DEBUG', false);
      define('SCRIPT_DEBUG', false);
      define('WP_DEBUG_LOG', false);
      define('WP_DEBUG_DISPLAY', false);
      define('WP_DISABLE_FATAL_ERROR_HANDLER', false);

      // Algolia settings
      define('ALGOLIA_APPLICATION_ID', 'XXXX');
      define('ALGOLIA_SEARCH_API_KEY', 'XXXX');
      define('ALGOLIA_API_KEY', 'XXXX');

      // Other settings
      define('OTGS_INSTALLER_SITE_KEY_WPML', 'XXXX');
   }
}
```

## WP CLI Commands

### Adding existing content

To reindex the global index,

`wp algolia update_algolia_index`

To update the index used for the map, add the `--index` argument:

e.g. `wp algolia update_algolia_index --index=map`

To reindex a non-English global index, add the `--lang` argument:

`wp algolia update_algolia_index --lang=chs`

e.g. `wp algolia update_algolia_index --index=map --lang=cht`

