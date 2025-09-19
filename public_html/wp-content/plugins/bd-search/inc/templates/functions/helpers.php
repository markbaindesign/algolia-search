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
        $registered_scripts = wp_scripts()->registered;
        $is_registered = $registered_scripts[$handle] ?? null;
        if ($is_registered) {
            return true;
        } else {
            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log(print_r('BD616__PLUGIN_DEBUG: ' .  $handle . ' is not registered! Cannot enqueue! (wp_scripts)'. PHP_EOL, true));
            }
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
            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                if (wp_scripts()->queue && in_array($handle, wp_scripts()->queue)) {
                    error_log(print_r('BD616__PLUGIN_DEBUG: ' . $handle . ' is enqueued (wp_scripts).' . PHP_EOL, true));
                }
            }
        }
    }
endif;
