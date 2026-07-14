# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Site

Local by Flywheel site: `http://stjodevdev.local/`  
Theme: `stjo-dgd` (active)  
WordPress path: `~/Local-Sites/stjodevdev/app/public/`

## WP-CLI

Every Local site shares the DB name `local`, so bare `wp` hits whichever site is first. Always pass the socket:

```bash
wp --path=~/Local-Sites/stjodevdev/app/public \
   --url=http://stjodevdev.local \
   --db-socket="$HOME/Library/Application Support/Local/run/EkBcVv_pZ/mysql/mysqld.sock" \
   <subcommand>
```

Flush page cache after any post_content update:

```bash
wp ... cache flush
wp ... post meta delete <ID> _et_pb_page_layout  # if relevant
```

## No build step

Pure PHP/CSS theme — no npm, no webpack. Edit files directly. The only external tool is the figma-to-wp-4.0 pipeline (see below), which runs outside this repo.

## CSS loading order

Functions.php enqueues in this exact dependency chain — always edit in the right file:

| File | Purpose |
|------|---------|
| `assets/css/fonts.css` | `@font-face` declarations only (self-hosted woff2) |
| `assets/css/tokens.css` | CSS custom properties from design tokens (generated) |
| `style.css` | Theme header only — intentionally empty body |
| `assets/css/main.css` | Base layout, typography, component rules |
| `assets/css/sections.css` | Per-section band rules (`.stjo-hero`, `.stjo-stats`, etc.) |
| `assets/css/overrides.css` | Last-resort block overrides, specificity fixes |
| `assets/css/editor.css` | Block editor canvas only — mirrors frontend chain |

`theme.json` is **settings-only** (palette, font sizes, spacing presets, widths). All actual CSS lives in the files above — do not add a `styles` key to theme.json.

## Architecture

**Hybrid classic theme:** PHP templates + block patterns + settings-only theme.json. No FSE (no `templates/` dir), no custom blocks yet.

**Homepage flow:**  
`front-page.php` → `get_header()` / `get_footer()` → renders the Home *Page*'s block content.  
The Home page content is seeded from `inc/patterns/home.php` via `seed.php`. After seeding, the live content lives in the DB and `home.php` is the seed source only.

**Block patterns** (`inc/patterns/*.php`) are auto-registered by `inc/block-patterns.php`. Each file is included with `ob_start`/`ob_get_clean`, so PHP is valid inside patterns (used for `$img = get_template_directory_uri() . '/assets/images'`).

**Client config** (`theme-config.json`) drives all client-specific content in templates without touching PHP: footer contact, social links, newsletter copy, nav fallback, header CTAs, partner logos. Read via `stjo_config_get( 'footer.contact' )` etc.

**Custom post type:** `student-story` (archive: `/student-stories/`). Registered in `inc/cpt-student-story.php`, auto-loaded by `inc/cpt-loader.php`.

**Block style variants** registered in `inc/block-styles.php`:
- `core/paragraph` → `eyebrow` (caps label)
- `core/image` → `rounded`
- `core/button` → `arrow-link` (arrow ghost link)

## Seeding pages

```bash
wp --path=... --url=... eval-file wp-content/themes/stjo-dgd/seed.php
```

`seed.php` reads `seed-manifest.json` and creates/updates pages idempotently by slug. **Delete both files before launch.**

## Figma-to-WP pipeline

Build workspace: `~/Local-Sites/stjodevdev/f2w-build/`  
Figma file key: `VnlGN3RYgYvnBN8e7iIPmh` (SJIS-Homepage)  
Key frames: Home `4948:1396`, Section Landing `4948:1609`, History `4948:1731`, Student Stories `4948:2003`

Pipeline output lands in `f2w-build/` — `asset-map.json` maps Figma image refs to filenames in `assets/images/`. Run `diff.py` to compare rendered page against Figma; exit 0 = converged.

Coverage reports (`coverage-*.md`) track which Figma sections mapped to which registry components and which were skipped (nav + footer = template parts, not post content).

## Design tokens

`tokens.css` and `theme.json` palette are generated from Figma variables. The mapping is documented in `tokens-report.md`. Key slugs used in block markup:

- Colors: `blue-900` (#003566), `blue-700` / `brand-dark` (#0053a0), `light` (#f3f3e6), `yellow` (#ffd520)
- Font families: `base` (DM Sans), `heading` (Caecilia LT Pro)
- Spacing: `small`, `medium`, `large` (used as `var:preset|spacing|large` in block attrs)
