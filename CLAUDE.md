# CLAUDE.md

This repo develops **bd-search** ("BD Algolia Search"), the studio's Algolia search plugin for WordPress, together with the DDEV test site it runs on. Studio project **ASP** (Algolia Search Plugin), registered in `bain-studio/docs/projects/asp.md`.

## Asana

ASANA_PROJECT_GID: 1208349738573318
ASANA_TASK_PREFIX: ASP
ASANA_PROJECT_NAME: Algolia Search Plugin

All tasks live in Asana, via `asana-mirror.md` and `bain-studio/studio/sync.py`. Don't keep a `TODO.md`.

## Layout

- `public_html/wp-content/plugins/bd-search/`: the plugin (the product). Entry `bd-search.php`; code under `inc/` (records, indices, WP-CLI, updates, admin, templates); unit tests under `tests/Unit/`.
- `_template/` + `new-child-plugin.sh`: scaffold for the per-site child plugin that holds a site's index config (post types per index, routing, index settings).
- `public_html/wp-content/plugins/bd-search-child/` and `bd-search-post-types/`: test-site child plugin and CPTs (team, project). They're tracked on `feature/public-release` only.
- `dist.sh` / `release.sh`: build the release zip into `release/`.

## Commands

```bash
ddev start                                   # site: https://algolia-search.ddev.site
ddev wp algolia check_env                    # confirm environment and Algolia app before indexing
ddev wp algolia update [--index=global] [--lang=es]   # full re-index (clears the index first)
ddev wp algolia update --post_ids=1,2        # update only those records (no clear)
ddev exec -d /var/www/html/public_html/wp-content/plugins/bd-search vendor/bin/phpunit
```

Host PHP lacks the extensions PHPUnit needs, so run tests through `ddev exec`. If the web container is down, use a one-off container from the ddev web image.

The local site uses its own test Algolia app (`BBY7J34O4Z`, kept alive by algolia-pulse), so it's safe for destructive tests. Check `check_env` before running anything against a different app.

The test site runs WPML (English default + Spanish), so it can test per-language indices (`wp_<index>_<lang>`).

## Conventions

- Git flow: `master` / `develop`, `feature/*`, `hotfix/x.y.z`, tags `X.Y.Z` (no `v`). Bump the version in `bd-search.php` (header + `BD616__PLUGIN_VERSION`), `README.md` and the plugin `README.md`.
- Prefixes: `bd324_` for hooks and functions, `BD616__` for plugin constants and newer functions.
- Third-party Algolia plugins are **reference only**, in `/media/data/Dropbox/Work/Resources/Dev/algolia-reference/` (see `NOTICE.md` there). Never copy code from them.
- Documents (reports, notes) go in `/media/data/Dropbox/Work/Projects/internal/bd-search/Docs/`, not in this repo.
