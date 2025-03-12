# BD Search Plugin

## v2.0.0

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
      // e.g. define('OTGS_INSTALLER_SITE_KEY_WPML', 'XXXX');

   } elseif ('development' == WP_ENVIRONMENT_TYPE) {
      // Dev environment settings

      // Debug settings
      define('WP_DEBUG', true);
      define('SCRIPT_DEBUG', true);
      define('WP_DEBUG_DISPLAY', false);
      define('WP_DEBUG_LOG', true);
      define('WP_DISABLE_FATAL_ERROR_HANDLER', true);

      // Other settings
      // e.g. define('OTGS_INSTALLER_SITE_KEY_WPML', 'XXXX');

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
      // e.g. define('OTGS_INSTALLER_SITE_KEY_WPML', 'XXXX');

   }
}
```

## WP CLI Commands

### Adding existing content

To update the global index,

`wp algolia update`

To update other indices, add the `--index` argument:

e.g. `wp algolia update --index=my-custom-index`

To reindex a non-English index, add the `--lang` argument:

e.g. `wp algolia update --lang=cht`

## Filters

The following filters can be used to modify the default Algolia index settings.

### `bd324_filter_algolia_index_config_searchableAttributes_{{full_index_name}}` 

Change the searchable attributes.

#### `bd324_filter_algolia_index_config_attributesForFaceting_{{full_index_name}}` 

Change the attibutes for faceting.

#### `bd324_filter_get_post_types_for_index_{{index_name}}`

Change the post types to be indexed.

### Template Parts

Default template parts can be changed with the following filter, which uses the unique template part ID.

This filter is used to add custom filters to the search interface. The unique IDs should match those in the custom JS file.

`BD616__filter_algolia_template_part_{{template_id}}`
