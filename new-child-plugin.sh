#!/usr/bin/env bash
set -euo pipefail

# -------------------------------------------------------
PREFIX=""
NAME=""
DEST="${PWD}"
CONST_NUM=$(( RANDOM % 9000 + 1000 ))

usage() {
    cat <<USAGE

Usage: ./new-child-plugin.sh --prefix <prefix> --name "<Site Name>" [options]

Options:
  -p, --prefix   Short site identifier, e.g. "kf"  (creates kf-search plugin)
  -n, --name     Human-readable site name, e.g. "Khyentse Foundation"
  -d, --dest     Parent directory to create the plugin in (default: current dir)
  -c, --const    4-digit constant number, e.g. 4321 (default: random)
  -h, --help     Show this help

Example:
  ./new-child-plugin.sh -p kf -n "Khyentse Foundation" -d /path/to/site/plugins

USAGE
}

# -------------------------------------------------------
while [[ $# -gt 0 ]]; do
    case "$1" in
        -p|--prefix) PREFIX="${2,,}"; shift 2 ;;
        -n|--name)   NAME="$2";       shift 2 ;;
        -d|--dest)   DEST="$2";       shift 2 ;;
        -c|--const)  CONST_NUM="$2";  shift 2 ;;
        -h|--help)   usage; exit 0 ;;
        *) echo "Unknown option: $1"; usage; exit 1 ;;
    esac
done

if [[ -z "$PREFIX" || -z "$NAME" ]]; then
    echo "Error: --prefix and --name are required"
    usage
    exit 1
fi

if [[ ! "$CONST_NUM" =~ ^[0-9]{4}$ ]]; then
    echo "Error: --const must be a 4-digit number"
    exit 1
fi

SLUG="${PREFIX}-search"
CONST="BD${CONST_NUM}__"
TEXT_DOMAIN="_${PREFIX}_search_plugin"
FULL_NAME="$NAME Algolia Search - Custom"
PLUGIN_DIR="$DEST/$SLUG"

# -------------------------------------------------------
echo ""
echo "New child plugin"
echo "  Slug:      $SLUG"
echo "  Name:      $FULL_NAME"
echo "  Constant:  ${CONST}"
echo "  Domain:    $TEXT_DOMAIN"
echo "  Directory: $PLUGIN_DIR"
echo ""

if [[ -d "$PLUGIN_DIR" ]]; then
    echo "Error: directory already exists: $PLUGIN_DIR"
    exit 1
fi

read -r -p "Create plugin? [y/N] " confirm
[[ "$confirm" =~ ^[Yy]$ ]] || { echo "Aborted."; exit 0; }
echo ""

# -------------------------------------------------------
# Directory structure
mkdir -p \
    "$PLUGIN_DIR/assets/js" \
    "$PLUGIN_DIR/assets/images" \
    "$PLUGIN_DIR/inc/indices" \
    "$PLUGIN_DIR/inc/records" \
    "$PLUGIN_DIR/inc/scripts" \
    "$PLUGIN_DIR/inc/styles" \
    "$PLUGIN_DIR/inc/helpers" \
    "$PLUGIN_DIR/inc/templates"

# -------------------------------------------------------
# Main plugin file
cat > "$PLUGIN_DIR/bd-search-custom.php" <<PHP
<?php

/*
 Plugin Name: $FULL_NAME
 Description: Adds project-specific indices, post types, filters, etc. to core BD Algolia Search plugin.
 Author: Bain Design
 Version: 1.0.0
 Author URI: http://bain.design
 License: GNU General Public License v2.0
 License URI: http://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: $TEXT_DOMAIN
 Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

if (
    !defined('ALGOLIA_APPLICATION_ID') ||
    !defined('ALGOLIA_SEARCH_API_KEY') ||
    !defined('ALGOLIA_API_KEY')
) {
    return;
}

define('${CONST}PLUGIN_FILE', __FILE__);
define('${CONST}PLUGIN_HANDLE', '$SLUG');
define('${CONST}PLUGIN_NAME', __('$FULL_NAME', '$TEXT_DOMAIN'));
define('${CONST}PLUGIN_VERSION', '1.0.0');
define('${CONST}PLUGIN_DIR', untrailingslashit(dirname(${CONST}PLUGIN_FILE)));
define('${CONST}PLUGIN_DIR_NAME', untrailingslashit(dirname(plugin_basename(${CONST}PLUGIN_FILE))));
\$plugin_dir_url = plugin_dir_url(__FILE__);
define('${CONST}PLUGIN_URL', \$plugin_dir_url);
define('${CONST}SCRIPTS_URL', ${CONST}PLUGIN_URL . 'assets/js');
define('${CONST}STYLES_URL', ${CONST}PLUGIN_URL . 'assets/css');
define('${CONST}IMAGES_URL', ${CONST}PLUGIN_URL . 'assets/images');

require_once ${CONST}PLUGIN_DIR . '/inc/index.php';

function ${CONST}load_textdomain()
{
    load_plugin_textdomain(
        '$TEXT_DOMAIN',
        false,
        ${CONST}PLUGIN_DIR_NAME . '/languages'
    );
}
add_action('init', '${CONST}load_textdomain');
PHP

# -------------------------------------------------------
# inc/index.php
cat > "$PLUGIN_DIR/inc/index.php" <<PHP
<?php // Inc

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

require_once ${CONST}PLUGIN_DIR . '/inc/indices/indices.php';
require_once ${CONST}PLUGIN_DIR . '/inc/records/index.php';
require_once ${CONST}PLUGIN_DIR . '/inc/shortcodes.php';
require_once ${CONST}PLUGIN_DIR . '/inc/updates.php';

require_once 'scripts/index.php';
require_once 'styles/index.php';
require_once 'helpers/index.php';
require_once 'templates/templates.php';
PHP

# -------------------------------------------------------
# inc/indices/indices.php
cat > "$PLUGIN_DIR/inc/indices/indices.php" <<PHP
<?php // Indices

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Register custom Algolia indices here.
// Use BD616__ hooks from the core plugin to add or modify indices.
//
// Example:
// add_filter('BD616__indices', function(\$indices) {
//     \$indices[] = new ${CONST}Index_Posts();
//     return \$indices;
// });
PHP

# -------------------------------------------------------
# inc/records/index.php
cat > "$PLUGIN_DIR/inc/records/index.php" <<PHP
<?php // Records

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Define custom record shapes for Algolia indices here.
PHP

# -------------------------------------------------------
# inc/scripts/index.php
cat > "$PLUGIN_DIR/inc/scripts/index.php" <<PHP
<?php // Scripts

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

function ${CONST}enqueue_scripts()
{
    // wp_enqueue_script(...);
}
add_action('wp_enqueue_scripts', '${CONST}enqueue_scripts');
PHP

# -------------------------------------------------------
# inc/styles/index.php
cat > "$PLUGIN_DIR/inc/styles/index.php" <<PHP
<?php // Styles

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

function ${CONST}enqueue_styles()
{
    // wp_enqueue_style(...);
}
add_action('wp_enqueue_scripts', '${CONST}enqueue_styles');
PHP

# -------------------------------------------------------
# inc/helpers/index.php
cat > "$PLUGIN_DIR/inc/helpers/index.php" <<PHP
<?php // Helpers

if (!defined('ABSPATH')) {
    die('Invalid request.');
}
PHP

# -------------------------------------------------------
# inc/templates/templates.php
cat > "$PLUGIN_DIR/inc/templates/templates.php" <<PHP
<?php // Templates

if (!defined('ABSPATH')) {
    die('Invalid request.');
}
PHP

# -------------------------------------------------------
# inc/shortcodes.php
cat > "$PLUGIN_DIR/inc/shortcodes.php" <<PHP
<?php // Shortcodes

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// add_shortcode('${PREFIX}_search', '${CONST}render_search');
PHP

# -------------------------------------------------------
# inc/updates.php
cat > "$PLUGIN_DIR/inc/updates.php" <<PHP
<?php // Updates

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Hook into save_post to push records to Algolia when posts are saved.
//
// function ${CONST}update_on_save(\$post_id, WP_Post \$post, \$update)
// {
//     bd324_update_algolia_record(\$post_id, \$post);
// }
// add_action('save_post', '${CONST}update_on_save', 10, 3);
PHP

# -------------------------------------------------------
echo "✓ Created $PLUGIN_DIR"
echo ""
echo "Next steps:"
echo "  1. Copy or symlink bd-search (core) into the same plugins directory"
echo "  2. Add Algolia keys to wp-config.php"
echo "  3. Define indices in inc/indices/indices.php"
echo "  4. Activate both plugins in WordPress"
echo ""
