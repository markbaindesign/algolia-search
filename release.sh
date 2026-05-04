#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PLUGIN_DIR="$SCRIPT_DIR/public_html/wp-content/plugins/bd-search"
PLUGIN_FILE="$PLUGIN_DIR/bd-search.php"
ROOT_README="$SCRIPT_DIR/README.md"
PLUGIN_README="$PLUGIN_DIR/README.md"
RELEASE_DIR="$SCRIPT_DIR/release"

# -------------------------------------------------------
usage() {
    echo "Usage: ./release.sh <version>"
    echo "Example: ./release.sh 2.9.0"
}

NEW_VERSION="${1:-}"

if [[ -z "$NEW_VERSION" ]]; then
    usage; exit 1
fi

if [[ ! "$NEW_VERSION" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
    echo "Error: version must be in format X.Y.Z"
    exit 1
fi

# -------------------------------------------------------
# Get current version from the authoritative define
CURRENT_VERSION=$(grep "BD616__PLUGIN_VERSION'" "$PLUGIN_FILE" | sed "s/.*'\([0-9][^']*\)'.*/\1/")

if [[ -z "$CURRENT_VERSION" ]]; then
    echo "Error: could not read current version from $PLUGIN_FILE"
    exit 1
fi

echo ""
echo "BD Algolia Search release"
echo "  $CURRENT_VERSION  →  $NEW_VERSION"
echo ""
read -r -p "Continue? [y/N] " confirm
[[ "$confirm" =~ ^[Yy]$ ]] || { echo "Aborted."; exit 0; }
echo ""

# -------------------------------------------------------
# Bump version
sed -i "s/Version: $CURRENT_VERSION/Version: $NEW_VERSION/" "$PLUGIN_FILE"
sed -i "s/BD616__PLUGIN_VERSION', '$CURRENT_VERSION'/BD616__PLUGIN_VERSION', '$NEW_VERSION'/" "$PLUGIN_FILE"
sed -i "s/Version: $CURRENT_VERSION/Version: $NEW_VERSION/" "$ROOT_README"

if [[ -f "$PLUGIN_README" ]]; then
    sed -i "s/$CURRENT_VERSION/$NEW_VERSION/g" "$PLUGIN_README"
fi

echo "✓ Version bumped in plugin file and README"

# -------------------------------------------------------
# Vendor check
if [[ ! -d "$PLUGIN_DIR/vendor" || -z "$(ls -A "$PLUGIN_DIR/vendor" 2>/dev/null)" ]]; then
    echo ""
    echo "Warning: vendor/ is empty or missing — run 'composer install' in the plugin dir first."
    read -r -p "Continue without vendor/? [y/N] " vendor_confirm
    [[ "$vendor_confirm" =~ ^[Yy]$ ]] || { echo "Aborted."; exit 0; }
fi

# -------------------------------------------------------
# Build release
RELEASE_PATH="$RELEASE_DIR/$NEW_VERSION"
rm -rf "$RELEASE_PATH"
mkdir -p "$RELEASE_PATH"

rsync -a \
    --exclude="node_modules/" \
    --exclude="src/" \
    --exclude="emergency-sql-reset.sql" \
    --exclude=".git" \
    --exclude="*.map" \
    "$PLUGIN_DIR/" "$RELEASE_PATH/bd-search/"

(cd "$RELEASE_PATH" && zip -rq "bd-search.$NEW_VERSION.zip" bd-search/)

echo "✓ Built: release/$NEW_VERSION/bd-search.$NEW_VERSION.zip"

# Update stable
rm -rf "$RELEASE_DIR/stable"
mkdir -p "$RELEASE_DIR/stable"
rsync -a "$RELEASE_PATH/bd-search/" "$RELEASE_DIR/stable/bd-search/"
cp "$RELEASE_PATH/bd-search.$NEW_VERSION.zip" "$RELEASE_DIR/stable/"

echo "✓ Stable updated: release/stable/"

# -------------------------------------------------------
# Git commit + tag
FILES_TO_COMMIT=("$PLUGIN_FILE" "$ROOT_README")
[[ -f "$PLUGIN_README" ]] && FILES_TO_COMMIT+=("$PLUGIN_README")

git -C "$SCRIPT_DIR" add "${FILES_TO_COMMIT[@]}"
git -C "$SCRIPT_DIR" commit -m "bump version to $NEW_VERSION"
git -C "$SCRIPT_DIR" tag "$NEW_VERSION"

echo "✓ Committed and tagged $NEW_VERSION"
echo ""
echo "To push:"
echo "  git push origin HEAD && git push origin $NEW_VERSION"
echo ""
