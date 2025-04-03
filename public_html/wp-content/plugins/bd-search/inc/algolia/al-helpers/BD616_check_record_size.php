<?php

if (!defined('ABSPATH')) {
   die('Invalid request, dude.');
}

/**
 * Handle Too Big Field Values
 * @since 1.0.0
 * @package BD616
 *
 * Algolia has a fit if the data are too big (> 10000b)
 * This function will check the size of the data
 * and return false if it is too big.
 * 
 * See https://www.algolia.com/pricing/ for limits per plan
 * 
 * At time of writing, the limit on the `Build` plan is 10 KB (10000 B)
 * 
 * Works on a string.
 * 
 * @param   $record           Record to check
 * @return  $record           Checked Record
 */
if (!function_exists('BD616_check_record_size')) :
   function BD616_check_record_size($record, $post_id)
   {
      $record_size = mb_strlen(serialize((array)$record), '8bit');
      $max_length = defined('ALGOLIA_MAX_RECORD_LENGTH') ? ALGOLIA_MAX_RECORD_LENGTH : 10000;
      if ($record_size > $max_length) {
         error_log(sprintf("Record #%d Exceeds Maximum Size Per Record (size=%d/%d)", $post_id, $record_size, $max_length));
         // error_log(print_r($record, true));
         return false;
      }
      return true;
   }
endif;
