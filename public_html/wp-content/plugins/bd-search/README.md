# BD Search Plugin

## v2.2.0
## By Bain Design

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

To update other indices, add the `--index` argument (without `wp_` prefix):

e.g. `wp algolia update --index=my-custom-index`

To reindex a non-English index, add the `--lang` argument:

e.g. `wp algolia update --lang=cht`

## Filters

The following filters can be used to modify the default Algolia index settings.

### Add tax terms to the index.

```
bd324_filter_taxonomies_to_index_{{$index_name}}
```

### Change the searchable attributes.

```
bd324_filter_algolia_index_config_searchableAttributes_{{full_index_name}}
```

### Change the attibutes for faceting.

```
bd324_filter_algolia_index_config_attributesForFaceting_{{full_index_name}}
```

### Change the post types to be indexed.

```
bd324_filter_get_post_types_for_index_{{index_name}}
```

### Change the trigger class for the Modal

Default: `.bd-search-modal-trigger`

```
bd324_filter_modal_trigger
```

## Template Parts

Default template parts can be changed with the following filter, which uses the unique template part ID.

This filter is used to add custom filters to the search interface. The unique IDs should match those in the custom JS file.

`BD616__filter_algolia_template_part_{{template_id}}`

The `{{template_id}}` is made up as follows:

`algolia-{{template_part_name}}-{{index_name}}--{{template_name}}`

## Shortcodes

One way to display a search form is to use the shortcode: `[bd-search]`

By default, this shortcode will display results from the `global` index.

### Attributes

* `index` - The index to search. Default is `global`.
* `template` - The template to use. Default is `NULL`.

## Templates

There is a default template. To load a custom template, use the `template` attribute in the shortcode.

Templates can be customised using filters. 

Note: IDs should match the custom JS for that template.

# Modal

If not required, the modal can be disabled by removing the action which registers the modal script.

```
remove_action('wp_footer', 'BD616__search_modal_overlay', 10);
remove_action('wp_footer', 'BD616__search_modal', 10);
remove_action('wp_enqueue_scripts', 'bd324_register_algolia_script_modal', 10);
```

## Styles

Styles can be customized using CSS variables. The following variables are available:

```

:root {
   /* Modal */
   --bd-search-modal-z-index: 20100;
   --bd-search-modal-top: 10vh;
   --bd-search-modal-height: auto;
   --bd-search-modal-width: 80vw;
   --bd-search-modal-max-width: 999px;
   --bd-search-modal-max-height: 80vh;
   --bd-search-modal-border: 1px solid #ddd;
   --bd-search-modal-border-radius: 5px;
   --bd-search-modal-box-shadow: none;
   --bd-search-modal-background-color: #eee;
   --bd-search-modal-padding: 1rem;
   --bd-search-modal-overlay-background-color: rgba(255, 255, 255, 0.75);
   --bd-search-modal-footer-border-top: 1px solid rgba(0, 0, 0, 0.1);
   --bd-search-modal-header-border-bottom: 1px solid rgba(0, 0, 0, 0.1);
   --bd-search-modal-form-filters-display: block;

   /* Form */

   /* Button */
   --bd-search-form-submit-button-display: none;
   --bd-search-form-submit-button-reset-font-size: 12px;
   --bd-search-form-submit-button-reset-transform: uppercase;

   /* Input */
   --bd-search-form-input-bg-color: #eee;
   --bd-search-form-input-padding: 10px 0 10px 45px;
   --bd-search-form-input-box-shadow: none;
   --bd-search-form-input-border-radius: 0;
   --bd-search-form-input-border-width: 1px;
   --bd-search-form-input-border-style: solid;
   --bd-search-form-input-border-color: #ddd;
   --bd-search-form-input-width: 100%;
   --bd-search-form-input-font-size: 16px;
   --bd-search-form-input-line-height: 1.7em;

   /* Input - Focus */
   --bd-search-form-input-focus-border-radius: 0;
   --bd-search-form-input-focus-border-width: 1px;
   --bd-search-form-input-focus-border-style: solid;
   --bd-search-form-input-focus-border-color: #ccc;
   --bd-search-form-input-focus-box-shadow: none;
   --bd-search-form-input-focus-border-style: solid;
   --bd-search-form-input-focus-outline: currentColor none medium;

   --bd-search-form-ais-RefinementList-count-display: none;
   --bd-search-form-ais-CurrentRefinements-display: none;
   --bd-search-form-ais-SearchBox-submit-display: none;
   --bd-search-form-ais-Stats-display: none;

   /* Buttons */
   --bd-search-form-button-color: inherit;
   --bd-search-form-button-bg-color: #eee;
   --bd-search-form-button-border-color: #ddd;
   --bd-search-form-button-border-radius: none;
   --bd-search-form-button-box-shadow: none;
   --bd-search-form-button-padding: inherit;
   --bd-search-button-font-size: inherit;
   --bd-search-button-font-weight: inherit;
   --bd-search-button-font-spacing: inherit;
   --bd-search-button-text-transform: inherit;

   /* Button - State - Hover */
   --bd-search-form-button-color-hover: inherit;
   --bd-search-form-button-bg-color-hover: #e7e7e7;
   --bd-search-form-button-border-color-hover: #ddd;
   --bd-search-form-button-box-shadow-hover: none;

   /* Button - State - Focus */
   --bd-search-form-button-color-focus: inherit;
   --bd-search-form-button-bg-color-focus: #e7e7e7;
   --bd-search-form-button-border-color-focus: #ddd;
   --bd-search-form-button-box-shadow-focus: none;

   /* Button - State - Disabled */
   --bd-search-form-button-color-disabled: #ddd;
   --bd-search-form-button-bg-color-disabled: none;
   --bd-search-form-button-border-color-disabled: #ddd;
   --bd-search-form-button-box-shadow-disabled: none;

   /* Results */
   --bd-search-form-results-highlight-color: inherit;
   --bd-search-form-results-highlight-bg-color: yellow;

   /* Results - Hit Card */

   /* Results - Hit Card - Footer */
   --bd-search-form-results-card-footer-display: block;

   /* Results - Pagination */
   --bd-search-form-results-ais-Pagination-link-color: inherit;
   --bd-search-form-results-ais-Pagination-link-bg-color: #eee;
   --bd-search-form-results-ais-Pagination-link-border: 1px solid #ddd;
   --bd-search-form-results-ais-Pagination-link-selected-color: inherit;
   --bd-search-form-results-ais-Pagination-link-selected-bg-color: #ddd;
   --bd-search-form-results-ais-Pagination-link-selected-border: 1px solid #ccc;

   /* Form - Filters */
   --bd-search-form-filter-display-age-range: block;
   --bd-search-form-filter-display-contributors: block;
   --bd-search-form-filter-display-method: block;
   --bd-search-form-filter-display-tradition: block;
   --bd-search-form-filter-display-category: block;
   --bd-search-form-filter-display-tags: block;

   /* Full Page */
   
}

```

# Custom Indexes

## Custom default Global index

By default, the plugin assumes an index called "global". 

Scripts for this index are loaded by default, along with 2 views ("compact" and "advanced").

To load a custom script for the default Global index, you should also remove the action which registers the default global script.

```
remove_action('wp_enqueue_scripts', 'bd324_register_algolia_script_global', 10);
remove_action('wp_enqueue_scripts', 'bd324_register_algolia_script_global_advanced', 10);
```

