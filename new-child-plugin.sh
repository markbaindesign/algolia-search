#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TEMPLATE_DIR="$SCRIPT_DIR/_template"

PREFIX=""
NAME=""
POST_TYPE=""
DEST="${PWD}"
CONST_NUM=$(( RANDOM % 9000 + 1000 ))

# -------------------------------------------------------
usage() {
    cat <<USAGE

Usage: ./new-child-plugin.sh --prefix <prefix> --name "<Name>" --post-type <slug> [options]

Options:
  -p, --prefix      Short site identifier, e.g. "kf"  → creates kf-search
  -n, --name        Human-readable site name, e.g. "Khyentse Foundation"
  -t, --post-type   Primary custom post type slug, e.g. "grant"
  -d, --dest        Parent directory for the new plugin (default: current dir)
  -c, --const       4-digit constant number, e.g. 4321 (default: random)
  -h, --help        Show this help

Example:
  ./new-child-plugin.sh -p kf -n "Khyentse Foundation" -t grant -d /path/to/plugins

USAGE
}

# -------------------------------------------------------
while [[ $# -gt 0 ]]; do
    case "$1" in
        -p|--prefix)    PREFIX="${2,,}"; shift 2 ;;
        -n|--name)      NAME="$2";       shift 2 ;;
        -t|--post-type) POST_TYPE="$2";  shift 2 ;;
        -d|--dest)      DEST="$2";       shift 2 ;;
        -c|--const)     CONST_NUM="$2";  shift 2 ;;
        -h|--help)      usage; exit 0 ;;
        *) echo "Unknown option: $1"; usage; exit 1 ;;
    esac
done

if [[ -z "$PREFIX" || -z "$NAME" || -z "$POST_TYPE" ]]; then
    echo "Error: --prefix, --name, and --post-type are required"
    usage
    exit 1
fi

if [[ ! "$CONST_NUM" =~ ^[0-9]{4}$ ]]; then
    echo "Error: --const must be a 4-digit number"
    exit 1
fi

if [[ ! -d "$TEMPLATE_DIR" ]]; then
    echo "Error: template not found at $TEMPLATE_DIR"
    exit 1
fi

SLUG="${PREFIX}-search"
CONST="BD${CONST_NUM}__"
TEXT_DOMAIN="_${PREFIX}_search_plugin"
FULL_NAME="$NAME Algolia Search - Custom"
POST_TYPE_LABEL="$(echo "${POST_TYPE:0:1}" | tr '[:lower:]' '[:upper:]')${POST_TYPE:1}"
PLUGIN_DIR="$DEST/$SLUG"

# -------------------------------------------------------
echo ""
echo "New child plugin"
echo "  Slug:       $SLUG"
echo "  Name:       $FULL_NAME"
echo "  Constant:   ${CONST}"
echo "  Post type:  $POST_TYPE  ($POST_TYPE_LABEL)"
echo "  Directory:  $PLUGIN_DIR"
echo ""

if [[ -d "$PLUGIN_DIR" ]]; then
    echo "Error: directory already exists: $PLUGIN_DIR"
    exit 1
fi

read -r -p "Create plugin? [y/N] " confirm
[[ "$confirm" =~ ^[Yy]$ ]] || { echo "Aborted."; exit 0; }
echo ""

# -------------------------------------------------------
# 1. Copy template
cp -r "$TEMPLATE_DIR" "$PLUGIN_DIR"

# 2. Rename __post_type__ directories (deepest paths first to avoid moving parents before children)
while IFS= read -r dir; do
    mv "$dir" "$(dirname "$dir")/$POST_TYPE"
done < <(find "$PLUGIN_DIR" -type d -name '__post_type__' | sort -r)

# 3. Replace all tokens in PHP files
# Order: longer/more-specific tokens first to avoid partial matches
find "$PLUGIN_DIR" -type f -name '*.php' | xargs sed -i \
    -e "s|__FULL_NAME__|$FULL_NAME|g" \
    -e "s|__CONST__|$CONST|g" \
    -e "s|__POST_TYPE_LABEL__|$POST_TYPE_LABEL|g" \
    -e "s|__post_type__|$POST_TYPE|g" \
    -e "s|__TEXT_DOMAIN__|$TEXT_DOMAIN|g" \
    -e "s|__NAME__|$NAME|g" \
    -e "s|__SLUG__|$SLUG|g" \
    -e "s|__PREFIX__|$PREFIX|g" \
    -e "s|__VERSION__|1.0.0|g"

# -------------------------------------------------------
echo "✓ Created $PLUGIN_DIR"
echo ""
echo "Next steps:"
echo "  1. Symlink bd-search (core) into the same plugins directory"
echo "  2. Confirm Algolia keys are in wp-config.php"
echo "  3. inc/indices/global/settings.php   — update searchableAttributes"
echo "  4. inc/indices/$POST_TYPE/settings.php — add searchableAttributes + attributesForFaceting"
echo "  5. inc/post-types/$POST_TYPE/add.php   — wire up taxonomy filters"
echo "  6. inc/indices/*/scripts.php          — register JS assets"
echo "  7. Activate both plugins in WordPress admin"
echo ""
