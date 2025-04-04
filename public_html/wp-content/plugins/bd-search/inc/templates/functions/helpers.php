<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude!');
}

/**
 * Checks if a WordPress script is registered.
 *
 * This function verifies whether a script with the given handle is registered
 * in WordPress. If the script is not registered, it logs an error message.
 *
 * @param string $handle The handle of the script to check.
 * @return bool True if the script is registered, false otherwise.
 */
if (!function_exists('bd324_check_script_is_registered')):
   function bd324_check_script_is_registered($handle)
   {
      if (wp_script_is($handle, 'registered')) {
         return true;
      } else {
         error_log(print_r($handle . ' is not registered! Cannot enqueue!', true));
         return false;
      }
   }
endif;

/**
 * Enqueues a script if it is registered.
 *
 * This function checks if a script with the given handle is registered in
 * WordPress. If it is, the script is enqueued.
 *
 * @param string $handle The handle of the script to enqueue.
 */
if (!function_exists('bd324_enqueue_script_if_registered')):
   function bd324_enqueue_script_if_registered($handle)
   {
      if (bd324_check_script_is_registered($handle)) {
         wp_enqueue_script($handle);
      }
   }
endif;
