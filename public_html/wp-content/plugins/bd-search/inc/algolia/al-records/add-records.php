<?php

if (!defined('ABSPATH')) {
    die('Invalid request, dude.');
}

require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-post-types/add-post-types.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-add-fields/add-fields.php';
require_once BD616__PLUGIN_DIR . '/inc/algolia/al-records/al-filters/add-filters.php';

/**
 * Convert post data to Algolia record with comprehensive size management
 *
 * Filter the post data. Convert to record.
 * All post types.
 *
 * @param WP_Post $post
 * @return $record
 */
if (!function_exists('bd324_convert_post_data')):
    function bd324_convert_post_data(WP_Post $post)
    {
        $post_id = $post->ID;
        $post_type = get_post_type($post_id);
        $max_record_size = apply_filters('bd324_algolia_max_record_size', 10000); // 100KB default

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [CONVERT] Starting conversion for Post #{$post_id} ({$post_type}) - Max size: {$max_record_size} bytes");
        }

        /**
         * Pass post data through a generic filter
         * See `add-records/add-all-post-types.php` for filters.
         */
        $record = (array) apply_filters(
            'all_post_types_to_record',
            $post
        );

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            $basic_size = bd324_calculate_record_size($record);
            error_log("BD324 DEBUG: [CONVERT] After basic filters - Size: {$basic_size} bytes, Fields: " . implode(', ', array_keys($record)));
        }

        /* Debug */
        $disallowed_post_types = [
           // 'post'
        ];
        if (in_array($post_type, $disallowed_post_types, true)) {
            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [CONVERT] Post #{$post_id} ({$post_type}) is in disallowed list - skipping");
            }
            return $record;
        }

        // Add ID if record doesn't already have one.
        if (!isset($record['objectID'])) {
            $record['objectID'] = implode('#', [$post->post_type, $post->ID]);
        }

        /**
         * PHASE 1: Apply post-type-specific filter to get complete record
         * This is where additional fields (including acf_content) are added
         */
        $filter_name = str_replace('-', '_', $post_type) . '_to_record';

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [CONVERT] Applying post-type filter: '{$filter_name}'");
        }

        $complete_record = apply_filters(
            $filter_name,
            $record,
            $post
        );

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            $complete_size = bd324_calculate_record_size($complete_record);
            $added_fields = array_diff(array_keys($complete_record), array_keys($record));
            error_log("BD324 DEBUG: [CONVERT] After post-type filter - Size: {$complete_size} bytes, Added fields: " . implode(', ', $added_fields));
        }

        /**
         * PHASE 2: Apply size management to the complete record
         * This ensures the final record (with all fields) doesn't exceed limits
         */
        $final_record = bd324_enforce_record_size_limit($complete_record, $post, $max_record_size);

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            $final_size = bd324_calculate_record_size($final_record);
            error_log("BD324 DEBUG: [CONVERT] Final record - Size: {$final_size} bytes, Fields: " . implode(', ', array_keys($final_record)));
        }

        return $final_record;
    }
endif;

/**
 * Enforce size limits on complete record with all fields
 * Prioritizes fields and truncates content fields as needed
 */
if (!function_exists('bd324_enforce_record_size_limit')):
    function bd324_enforce_record_size_limit($record, $post, $max_size)
    {
        $current_size = bd324_calculate_record_size($record);

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Current size: {$current_size}/{$max_size} bytes");
        }

        // If record fits, return as-is
        if ($current_size <= $max_size) {
            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Record fits within limit, no truncation needed");
            }
            bd324_clear_truncation_metadata($post->ID);
            return $record;
        }

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Record exceeds limit, starting optimization process");
        }

        // Define field priorities (higher priority = keep, lower priority = truncate first)
        $field_priorities = apply_filters('bd324_field_priorities', [
           // Priority 1: Essential fields (never remove)
           'objectID' => 100,
           'post_id' => 100,
           'post_title' => 100,
           'post_link' => 100,
           'post_type' => 100,
           'post_date' => 90,
           'post_image' => 85,
           'post_excerpt' => 80,

           // Priority 2: Content fields (truncate these)
           'content' => 10,
           'acf_content' => 5, // Truncate this first as it's usually largest

           // Priority 3: Other fields (medium priority)
           // Add other field priorities as needed
        ], $post);

        // Separate fields by priority
        $essential_fields = [];
        $content_fields = [];
        $other_fields = [];

        foreach ($record as $field_name => $field_value) {
            $priority = isset($field_priorities[$field_name]) ? $field_priorities[$field_name] : 50;

            if ($priority >= 80) {
                $essential_fields[$field_name] = $field_value;
            } elseif ($priority <= 20) {
                $content_fields[$field_name] = $field_value;
            } else {
                $other_fields[$field_name] = $field_value;
            }
        }

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Field categorization:");
            error_log("  Essential: " . implode(', ', array_keys($essential_fields)));
            error_log("  Content: " . implode(', ', array_keys($content_fields)));
            error_log("  Other: " . implode(', ', array_keys($other_fields)));
        }

        // Start with essential fields
        $optimized_record = $essential_fields;
        $current_size = bd324_calculate_record_size($optimized_record);

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Essential fields size: {$current_size} bytes");
        }

        // If essential fields exceed limit, we have a serious problem
        if ($current_size >= $max_size) {
            error_log("BD324 CRITICAL: Post #{$post->ID} essential fields exceed size limit! ({$current_size}/{$max_size} bytes)");
            bd324_update_truncation_metadata($post->ID, [
               'status' => 'essential_fields_too_large',
               'essential_size' => $current_size,
               'max_size' => $max_size,
               'timestamp' => current_time('mysql')
            ]);
            return $optimized_record;
        }

        // Add other fields if they fit
        $added_other_fields = 0;
        foreach ($other_fields as $field_name => $field_value) {
            $test_record = $optimized_record;
            $test_record[$field_name] = $field_value;
            $test_size = bd324_calculate_record_size($test_record);

            if ($test_size <= $max_size) {
                $optimized_record[$field_name] = $field_value;
                $current_size = $test_size;
                $added_other_fields++;

                if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                    error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Added field '{$field_name}' - Size now: {$current_size} bytes");
                }
            } else {
                if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                    error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Skipped field '{$field_name}' (would exceed limit)");
                }
            }
        }

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Added {$added_other_fields}/" . count($other_fields) . " other fields");
        }

        // Add content fields with truncation (in priority order)
        $content_field_names = array_keys($content_fields);
        usort($content_field_names, function ($a, $b) use ($field_priorities) {
            $priority_a = isset($field_priorities[$a]) ? $field_priorities[$a] : 50;
            $priority_b = isset($field_priorities[$b]) ? $field_priorities[$b] : 50;
            return $priority_b - $priority_a; // Higher priority first
        });

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Processing content fields in priority order: " . implode(', ', $content_field_names));
        }

        $truncation_info = [];
        foreach ($content_field_names as $field_name) {
            $field_value = $content_fields[$field_name];
            // Use calculate_record_size for accurate length (arrays allowed)
            $test_value = is_array($field_value) ? bd324_flatten_content($field_value) : $field_value;
            $original_length = bd324_calculate_record_size([$field_name => $test_value]);

            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Processing content field '{$field_name}' (original: {$original_length} bytes)");
            }

            // Calculate available space
            $available_space = $max_size - bd324_calculate_record_size($optimized_record) - 100; // Reserve 100 bytes

            if ($available_space <= 100) {
                // No space for this field
                $truncation_info[$field_name] = [
                   'original_length' => $original_length,
                   'final_length' => 0,
                   'status' => 'removed_no_space'
                ];

                if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                    error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - No space for field '{$field_name}' (available: {$available_space} bytes)");
                }
                continue;
            }

            // Try to fit content with truncation
            $truncated_content = bd324_binary_search_truncate_field(
                $optimized_record,
                $field_name,
                $field_value,
                $max_size
            );

            if (!empty($truncated_content)) {
                $optimized_record[$field_name] = $truncated_content;
                $final_length = strlen($truncated_content);
                $truncation_info[$field_name] = [
                   'original_length' => $original_length,
                   'final_length' => $final_length,
                   'status' => $original_length > $final_length ? 'truncated' : 'full'
                ];

                if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                    $status = $original_length > $final_length ? 'truncated' : 'full';
                    error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Field '{$field_name}' {$status} ({$original_length} → {$final_length} bytes)");
                }
            } else {
                $truncation_info[$field_name] = [
                   'original_length' => $original_length,
                   'final_length' => 0,
                   'status' => 'removed_too_large'
                ];

                if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                    error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Field '{$field_name}' removed (too large to fit)");
                }
            }
        }

        // Update metadata with truncation info
        if (!empty($truncation_info)) {
            $original_size = bd324_calculate_record_size($record);
            $final_size = bd324_calculate_record_size($optimized_record);

            bd324_update_truncation_metadata($post->ID, [
               'status' => 'fields_processed',
               'original_size' => $original_size,
               'final_size' => $final_size,
               'max_size' => $max_size,
               'field_details' => $truncation_info,
               'timestamp' => current_time('mysql')
            ]);

            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Truncation metadata saved ({$original_size} → {$final_size} bytes)");
            }
        }

        // Final safety check
        $final_size = bd324_calculate_record_size($optimized_record);
        if ($final_size > $max_size) {
            error_log("BD324 CRITICAL: Record #{$post->ID} still exceeds limit after optimization! Size: {$final_size}/{$max_size} bytes");
        } else {
            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [SIZE_LIMIT] Post #{$post->ID} - Final record within limits: {$final_size}/{$max_size} bytes");
            }
        }

        return $optimized_record;
    }
endif;

/**
 * Binary search to find optimal content length for a specific field
 */
if (!function_exists('bd324_binary_search_truncate_field')):
    function bd324_binary_search_truncate_field($base_record, $field_name, $content, $max_record_size)
    {
        // Flatten arrays to strings immediately
        if (is_array($content)) {
            $content = bd324_flatten_content($content);
            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [BINARY_SEARCH] Flattened array content for field '{$field_name}' to string (" . strlen($content) . " bytes)");
            }
        }

        $min = 0;
        $max = strlen($content);
        $best_content = '';
        $iterations = 0;

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [BINARY_SEARCH] Starting binary search for field '{$field_name}' (content length: {$max} bytes)");
        }

        // Handle empty content
        if ($max === 0) {
            return '';
        }

        while ($min <= $max) {
            $iterations++;
            $mid = intval(($min + $max) / 2);
            $test_content = bd324_smart_truncate($content, $mid);

            $test_record = $base_record;
            $test_record[$field_name] = $test_content;
            $test_size = bd324_calculate_record_size($test_record);

            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [BINARY_SEARCH] Iteration {$iterations}: mid={$mid}, content_len=" . strlen($test_content) . ", record_size={$test_size}");
            }

            if ($test_size <= $max_record_size) {
                $best_content = $test_content;
                $min = $mid + 1;
            } else {
                $max = $mid - 1;
            }
        }

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [BINARY_SEARCH] Completed after {$iterations} iterations - Best content length: " . strlen($best_content) . " bytes");
        }

        return $best_content;
    }
endif;

/**
 * Flatten array content to a searchable string
 */
if (!function_exists('bd324_flatten_content')):
    function bd324_flatten_content($content)
    {
        if (!is_array($content)) {
            return (string) $content;
        }

        $flattened_parts = [];

        foreach ($content as $key => $value) {
            if (is_array($value)) {
                // Recursively flatten nested arrays
                $flattened_parts[] = bd324_flatten_content($value);
            } elseif (is_string($value) || is_numeric($value)) {
                // Add meaningful values
                $flattened_parts[] = (string) $value;
            }
            // Skip objects, nulls, booleans, etc.
        }

        // Join with spaces for searchability
        $flattened = implode(' ', array_filter($flattened_parts));

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [FLATTEN] Flattened array with " . count($content) . " elements to " . strlen($flattened) . " byte string");
        }

        return $flattened;
    }
endif;

/**
 * Smart truncation that preserves word boundaries (for strings)
 */
if (!function_exists('bd324_smart_truncate')):
    function bd324_smart_truncate($text, $max_bytes)
    {
        // Handle non-string input
        if (!is_string($text)) {
            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [TRUNCATE] Input is not a string (" . gettype($text) . "), converting to string");
            }
            $text = (string) $text;
        }

        if (strlen($text) <= $max_bytes) {
            return $text;
        }

        if ($max_bytes <= 3) {
            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [TRUNCATE] Max bytes too small ({$max_bytes}), returning empty string");
            }
            return '';
        }

        // Truncate to max bytes, reserve space for ellipsis
        $truncated = substr($text, 0, max(0, $max_bytes - 3));

        // Try to find last complete word
        $last_space = strrpos($truncated, ' ');
        if ($last_space !== false && $last_space > (strlen($truncated) * 0.8)) {
            $truncated = substr($truncated, 0, $last_space);

            if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
                error_log("BD324 DEBUG: [TRUNCATE] Truncated at word boundary (position: {$last_space})");
            }
        }

        return $truncated . '...';
    }
endif;

// Add the helper functions from the previous response
if (!function_exists('bd324_calculate_record_size')):
    function bd324_calculate_record_size($record)
    {
        $size = strlen(json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true && isset($record['post_id'])) {
            // Only log for specific debugging scenarios to avoid log spam
            $debug_posts = apply_filters('bd324_debug_record_size_posts', []);
            if (empty($debug_posts) || in_array($record['post_id'], $debug_posts)) {
                error_log("BD324 DEBUG: [CALC_SIZE] Record size calculated: {$size} bytes");
            }
        }

        return $size;
    }
endif;

if (!function_exists('bd324_update_truncation_metadata')):
    function bd324_update_truncation_metadata($post_id, $metadata)
    {
        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [METADATA] Updating truncation metadata for Post #{$post_id} - Status: {$metadata['status']}");
        }

        update_post_meta($post_id, '_bd324_algolia_truncation_data', $metadata);

        // Also maintain a global list for admin screens
        $truncated_posts = get_option('bd324_truncated_posts', []);
        $truncated_posts[$post_id] = $metadata;
        update_option('bd324_truncated_posts', $truncated_posts);
    }
endif;

if (!function_exists('bd324_clear_truncation_metadata')):
    function bd324_clear_truncation_metadata($post_id)
    {
        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 DEBUG: [METADATA] Clearing truncation metadata for Post #{$post_id}");
        }

        delete_post_meta($post_id, '_bd324_algolia_truncation_data');

        $truncated_posts = get_option('bd324_truncated_posts', []);
        if (isset($truncated_posts[$post_id])) {
            unset($truncated_posts[$post_id]);
            update_option('bd324_truncated_posts', $truncated_posts);
        }
    }
endif;
