<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

if (!function_exists('bd324_handle_big_data_in_value')) :
   /**
    * Handle Too Big Field Values
    *
    * Algolia has a fit if the data are too big.
    * This function will check the size of the data
    * and return false if it is too big.
    * 
    * Works on a string.
    * 
    * @param   $field
    * @return  $value            If not too big or null
    */
   function bd324_handle_big_data_in_value($field)
   {
      $checked_field = array();

      if (isset($field['value'])) {
         $value = $field['value'];
      } else {
         return;
      }

      if (isset($field['name'])) {
         $name = $field['name'];
      } else {
         return;
      }

      if (is_array($value)) {
         return;
      }

      if (strlen($value) > 50000) {
         error_log(print_r("Field has been skipped, is > 50,000 bytes! [' . $name .  ']", true));
         return;
      } else {
         $value = strip_tags($value);
         $checked_field[$name] = $value;
      }
      return $checked_field;
   }
endif;