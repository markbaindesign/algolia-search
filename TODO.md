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

## Core plugin (`bd-search`)

- [ ] Expand test coverage to `update_algolia_index.php` and `update_records.php`
- [ ] Fix the two documented bugs above once decided on correct behaviour
