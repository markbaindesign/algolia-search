<?php

if (!defined('ABSPATH')) {
    die('Invalid request, dude.');
}

/**
 * Convert Post data to Algolia record with strict size management
 */
function algolia_add_all_post_types_to_record(WP_Post $post)
{
    // Define maximum record size in bytes (Algolia limit is typically 100KB)
    $max_record_size = apply_filters('bd324_algolia_max_record_size', 10000); // 10000 bytes default

    $record = [];

    // PRIORITY 1: Essential fields (always included first)
    $record = apply_filters('add_to_record_object_id', $record, $post);
    $record = apply_filters('add_to_record_post_id', $record, $post);
    $record = apply_filters('add_to_record_post_title', $record, $post);
    $record = apply_filters('add_to_record_post_link', $record, $post);
    $record = apply_filters('add_to_record_post_type', $record, $post);

    // PRIORITY 2: Important metadata
    $record = apply_filters('add_to_record_post_date', $record, $post);
    $record = apply_filters('add_to_record_post_image', $record, $post);
    $record = apply_filters('add_to_record_post_excerpt', $record, $post);

    // Calculate size without content
    $record_without_content = $record;
    $size_without_content = bd324_calculate_record_size($record_without_content);

    // Emergency fallback: If essential fields exceed limit, log and return minimal record
    if ($size_without_content >= $max_record_size) {
        $minimal_record = [
           'objectID' => $post->ID,
           'post_id' => $post->ID,
           'post_title' => wp_trim_words($post->post_title, 10, '...'),
           'post_type' => $post->post_type,
           'error' => 'essential_fields_too_large'
        ];

        bd324_log_record_error($post, 'essential_fields_exceed_limit', $size_without_content, $max_record_size);
        return $minimal_record;
    }

    // PRIORITY 3: Content (added last with dynamic truncation)
    $remaining_space = $max_record_size - $size_without_content;
    $record = bd324_add_content_with_size_limit($record, $post, $remaining_space, $max_record_size);

    // FINAL SAFETY CHECK: Ensure we never exceed the limit
    $final_size = bd324_calculate_record_size($record);
    if ($final_size > $max_record_size) {
        error_log("BD324 CRITICAL: Record #{$post->ID} exceeds limit after processing! Size: {$final_size} bytes");

        // Emergency truncation - remove content entirely if needed
        unset($record['content']);
        $final_size = bd324_calculate_record_size($record);

        bd324_update_truncation_metadata($post->ID, [
           'status' => 'emergency_content_removed',
           'original_size' => $final_size + 1000, // Estimate
           'final_size' => $final_size,
           'max_size' => $max_record_size,
           'timestamp' => current_time('mysql'),
           'fields_removed' => ['content']
        ]);
    }

    return $record;
}

/**
 * Calculate the exact byte size of a record
 */
function bd324_calculate_record_size($record)
{
    return strlen(json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

/**
 * Add content field with strict size enforcement
 */
function bd324_add_content_with_size_limit($record, $post, $available_bytes, $max_record_size)
{
    // Get content using existing filter
    $content_record = [];
    $content_record = apply_filters('add_to_record_post_content', $content_record, $post);

    // If no content was added, return original record
    if (empty($content_record) || !isset($content_record['content'])) {
        bd324_clear_truncation_metadata($post->ID);
        return $record;
    }

    $original_content = $content_record['content'];
    $original_content_length = strlen($original_content);

    // Reserve space for JSON encoding overhead
    $json_overhead = 100; // Conservative estimate for quotes, escaping, etc.
    $max_content_bytes = max(0, $available_bytes - $json_overhead);

    if ($max_content_bytes <= 100) { // Need minimum space for meaningful content
        bd324_update_truncation_metadata($post->ID, [
           'status' => 'no_space_for_content',
           'original_size' => $original_content_length,
           'final_size' => 0,
           'max_size' => $max_record_size,
           'available_space' => $available_bytes,
           'timestamp' => current_time('mysql')
        ]);

        error_log("BD324 Warning: Record #{$post->ID} has no space for content (available: {$available_bytes} bytes)");
        return $record;
    }

    // Try adding full content first
    $test_record = $record;
    $test_record['content'] = $original_content;
    $test_size = bd324_calculate_record_size($test_record);

    if ($test_size <= $max_record_size) {
        // Content fits perfectly
        $record['content'] = $original_content;
        bd324_clear_truncation_metadata($post->ID);

        if (defined('BD616__PLUGIN_DEBUG') && BD616__PLUGIN_DEBUG === true) {
            error_log("BD324 Debug: Record #{$post->ID} content fits perfectly (size: {$test_size}/{$max_record_size} bytes)");
        }
    } else {
        // Need to truncate - use binary search for optimal size
        $truncated_content = bd324_binary_search_truncate($record, $original_content, $max_record_size);
        $record['content'] = $truncated_content;

        bd324_update_truncation_metadata($post->ID, [
           'status' => 'content_truncated',
           'original_size' => $original_content_length,
           'final_size' => strlen($truncated_content),
           'max_size' => $max_record_size,
           'truncation_ratio' => round((strlen($truncated_content) / $original_content_length) * 100, 2),
           'timestamp' => current_time('mysql')
        ]);

        error_log("BD324 Notice: Record #{$post->ID} content truncated (original: {$original_content_length} → final: " . strlen($truncated_content) . " bytes)");
    }

    return $record;
}

/**
 * Use binary search to find optimal content length
 */
function bd324_binary_search_truncate($base_record, $content, $max_record_size)
{
    $min = 0;
    $max = strlen($content);
    $best_content = '';

    while ($min <= $max) {
        $mid = intval(($min + $max) / 2);
        $test_content = bd324_smart_truncate($content, $mid);

        $test_record = $base_record;
        $test_record['content'] = $test_content;
        $test_size = bd324_calculate_record_size($test_record);

        if ($test_size <= $max_record_size) {
            $best_content = $test_content;
            $min = $mid + 1;
        } else {
            $max = $mid - 1;
        }
    }

    return $best_content;
}

/**
 * Smart truncation that preserves word boundaries
 */
function bd324_smart_truncate($text, $max_bytes)
{
    if (strlen($text) <= $max_bytes) {
        return $text;
    }

    // Truncate to max bytes
    $truncated = substr($text, 0, max(0, $max_bytes - 3)); // Reserve space for ellipsis

    // Try to find last complete word
    $last_space = strrpos($truncated, ' ');
    if ($last_space !== false && $last_space > (strlen($truncated) * 0.8)) {
        $truncated = substr($truncated, 0, $last_space);
    }

    return $truncated . '...';
}

/**
 * Update truncation metadata for a post
 */
function bd324_update_truncation_metadata($post_id, $metadata)
{
    update_post_meta($post_id, '_bd324_algolia_truncation_data', $metadata);

    // Also maintain a global list for admin screens
    $truncated_posts = get_option('bd324_truncated_posts', []);
    $truncated_posts[$post_id] = $metadata;
    update_option('bd324_truncated_posts', $truncated_posts);
}

/**
 * Clear truncation metadata (when post fits without truncation)
 */
function bd324_clear_truncation_metadata($post_id)
{
    delete_post_meta($post_id, '_bd324_algolia_truncation_data');

    $truncated_posts = get_option('bd324_truncated_posts', []);
    if (isset($truncated_posts[$post_id])) {
        unset($truncated_posts[$post_id]);
        update_option('bd324_truncated_posts', $truncated_posts);
    }
}

/**
 * Log critical record errors
 */
function bd324_log_record_error($post, $error_type, $actual_size, $max_size)
{
    $error_data = [
       'status' => $error_type,
       'actual_size' => $actual_size,
       'max_size' => $max_size,
       'timestamp' => current_time('mysql'),
       'post_title' => $post->post_title,
       'post_type' => $post->post_type
    ];

    bd324_update_truncation_metadata($post->ID, $error_data);

    error_log("BD324 CRITICAL ERROR: {$error_type} for post #{$post->ID} - Size: {$actual_size}/{$max_size} bytes");
}

/**
 * Get truncation data for a specific post (for frontend display)
 */
function bd324_get_post_truncation_data($post_id)
{
    return get_post_meta($post_id, '_bd324_algolia_truncation_data', true);
}

/**
 * Get all truncated posts (for admin screens)
 */
function bd324_get_all_truncated_posts()
{
    return get_option('bd324_truncated_posts', []);
}

add_filter('all_post_types_to_record', 'algolia_add_all_post_types_to_record');
