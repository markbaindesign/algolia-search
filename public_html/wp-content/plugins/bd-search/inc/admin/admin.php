<?php // Admin

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

add_action('add_meta_boxes_portfolio', 'BD616__add_metabox');

function BD616__add_metabox()
{

   add_meta_box(
      'misha_metabox', // metabox ID
      'Promoted Search', // title
      'misha_metabox_callback', // callback function
      'portfolio', // add meta box to custom post type (or post types in array)
      'side', // position (normal, side, advanced)
      'high', // priority (default, low, high, core)
      array(
         '__block_editor_compatible_meta_box' => false, // Not Gutenberg compatible
      )
   );
}

// it is a callback function which actually displays the content of the meta box
function misha_metabox_callback($post)
{
   wp_nonce_field('S7ltVgRZNtaLmisw', '_bd324nonce');
   $search_priority = get_post_meta($post->ID, 'search_priority', true);
?>
   ID: <?php echo $post->ID; ?>
   Priority: <?php echo $search_priority; ?>
   <label for="wporg_field">Select a priority</label>
   <input type="number" id="search_priority" name="search_priority" step="5" min="0" value=<?php echo $search_priority ?> />
<?php

}

add_action('save_post_portfolio', 'misha_save_meta', 10, 2);

function misha_save_meta($post_id, $post)
{

   // nonce check
   if (!isset($_POST['_bd324nonce']) || !wp_verify_nonce($_POST['_bd324nonce'], 'S7ltVgRZNtaLmisw')) {
      return $post_id;
   }

   // check current user permissions
   $post_type = get_post_type_object($post->post_type);

   if (!current_user_can($post_type->cap->edit_post, $post_id)) {
      return $post_id;
   }

   // Do not save the data if autosave
   if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $post_id;
   }

   // define your own post type here
   if ('portfolio' !== $post->post_type) {
      return $post_id;
   }

   if (isset($_POST['search_priority'])) {
      update_post_meta($post_id, 'search_priority', $_POST['search_priority']);
   } else {
      delete_post_meta($post_id, 'search_priority');
   }

   return $post_id;
}

add_action('admin_menu', 'bd324_admin_menu');

function bd324_admin_menu()
{
   add_menu_page(
      'Algolia Search',        // Page title
      'Search',        // Menu title
      'manage_options',      // Capability
      'bd324-search',        // Menu slug
      'bd324_search_admin_page',    // Callback function
      'dashicons-search', // Icon
      100                    // Position
   );
}

function bd324_search_admin_page()
{
?>
   <div class="wrap">
      <h1>Search Admin</h1>

   </div>
   <?php

   // Get list of indexes
   global $algolia;
   $indexes = $algolia->listIndices();
   echo '<h2>Algolia Indexes</h2>';
   if (!empty($indexes['items'])) {
      echo '<ul>';
      echo '<table class="widefat"><thead><tr><th>Index Name</th><th>Entries</th><th>Last Updated</th><th>Primary</th><th>Replicas</th><th>Actions</th></tr></thead><tbody>';
      foreach ($indexes['items'] as $index) {
         echo '<tr>';
         echo '<td>' . esc_html($index['name']) . '</td>';
         echo '<td>' . esc_html($index['entries']) . '</td>';
         $updated_at = isset($index['updatedAt']) ? date('Y-m-d H:i:s', strtotime($index['updatedAt'])) : 'N/A';
         echo '<td>' . esc_html($updated_at) . '</td>';
         echo '<td>' . (isset($index['primary']) ? esc_html($index['primary']) : '—') . '</td>';

         // Replicas column
         if (isset($index['replicas']) && is_array($index['replicas']) && count($index['replicas']) > 0) {
            echo '<td>' . esc_html(implode(', ', $index['replicas'])) . '</td>';
         } else {
            echo '<td>—</td>';
         }

         // Actions column: show sync button if primary (has no 'primary' key)
         echo '<td>';
         if (!isset($index['primary'])) {
            // Sync form
            echo '<form method="post" style="margin:0;">';
            wp_nonce_field('bd324_sync_index_' . esc_attr($index['name']), 'bd324_sync_nonce');
            echo '<input type="hidden" name="bd324_sync_index" value="' . esc_attr($index['name']) . '">';
            echo '<input type="submit" class="button" value="Sync Index">';
            echo '</form>';

            // Handle sync button press for this index
            if (
               isset($_POST['bd324_sync_index']) &&
               $_POST['bd324_sync_index'] === $index['name'] &&
               isset($_POST['bd324_sync_nonce']) &&
               check_admin_referer('bd324_sync_index_' . $index['name'], 'bd324_sync_nonce')
            ) {
               if (function_exists('bd324_algolia_update_command')) {
                  $index_name = preg_replace('/^wp_/', '', $index['name']);
                  bd324_algolia_update_command($index_name, [], null, null);
                  echo '<div class="notice notice-success is-dismissible"><p>Index "' . esc_html($index['name']) . '" synced successfully!</p></div>';
               } else {
                  echo '<div class="notice notice-error is-dismissible"><p>Function bd324_algolia_update_command does not exist!</p></div>';
               }
            }
         } else {
            echo '—';
         }
         echo '</td>';

         echo '</tr>';
      }
      echo '</tbody></table>';
      echo '</ul>';
   ?>
      <form method="post">
         <?php
         // Security field
         wp_nonce_field('bd324_run_function', 'bd324_nonce');
         ?>
         <input type="submit" name="bd324_run" class="button button-primary" value="Sync All Indexes">
      </form>
   <?php
      // Check if button was pressed
      if (isset($_POST['bd324_run']) && check_admin_referer('bd324_run_function', 'bd324_nonce')) {
         if (function_exists('bd324_algolia_update_command')) {
            foreach ($indexes['items'] as $index) {
               $index_name = preg_replace('/^wp_/', '', $index['name']);
               bd324_algolia_update_command($index_name, [], null, null);
               echo '<div class="notice notice-success is-dismissible"><p>Index "' . esc_html($index['name']) . '" synced successfully!</p></div>';
            }
         }
      }
   } else {
      echo '<p>No indexes found.</p>';
   }
}
