# TODO

## Bugs (documented in test suite)

- [ ] **Search priority ignores empty meta** — `intval('')` returns `0`, and `is_numeric(0)` is `true`, so posts with no priority meta get assigned priority `0` instead of being skipped. See `SearchPriorityTest`.
- [ ] **Divi shortcode removal only strips opening tags** — the regex in `bd324_remove_divi_shortcodes` does not remove closing `[/et_pb_*]` tags. See `ContentFiltersTest`.

## Scaffold (`new-child-plugin.sh`)

- [ ] Test in a real VVV project (not just `/tmp/test-plugins`)
- [ ] Add `--index` flag to let caller skip the CPT index and generate a global-only child plugin

## Template (`_template/`)

- [ ] Add commented `_geoloc` example to `__post_type__/add.php` for projects with a location field
- [ ] Add commented ACF date → Unix timestamp example to `__post_type__/add.php`

## Admin UX

- [ ] When no indexes are found on the sync screen, show a button to run the initial index for each post type
  Runs `wp algolia update --index=<type>` (and `--index=global`) via an admin-ajax or REST action. Solves the first-time setup friction where the user has to drop to the CLI to populate indexes.

## Core plugin (`bd-search`)

- [ ] Expand test coverage to `update_algolia_index.php` and `update_records.php`
- [ ] Fix the two documented bugs above once decided on correct behaviour
