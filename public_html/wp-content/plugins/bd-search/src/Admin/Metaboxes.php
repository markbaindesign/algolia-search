<?php

namespace BD324\Search\Admin;

class Metaboxes
{
    public function register()
    {
        add_action('add_meta_boxes', array($this, 'register_meta_boxes'));
        add_action('save_post', array($this, 'save'), 10, 2);
    }

    public function register_meta_boxes()
    {

        \add_meta_box(
            'misha_metabox', // metabox ID
            'Promoted Search', // title
            [ $this, 'callback' ], // callback function
            'portfolio', // add meta box to custom post type (or post types in array)
            'side', // position (normal, side, advanced)
            'high', // priority (default, low, high, core)
            array(
               '__block_editor_compatible_meta_box' => false, // Not Gutenberg compatible
            )
        );
    }

    public function callback($post)
    {
        wp_nonce_field('S7ltVgRZNtaLmisw', '_bd324nonce');
        $search_priority = get_post_meta($post->ID, 'search_priority', true);
        ?>
      ID: <?php echo $post->ID; ?>
      Priority: <?php echo $search_priority; ?>
      <label for="wporg_field">Select a priority</label>
      <input type="number" id="search_priority" name="search_priority" step="5" min="0" value="<?php echo $search_priority ?>" />
        <?php

    }

    public function save($post_id, $post)
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
}
