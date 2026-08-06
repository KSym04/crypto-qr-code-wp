# Crypto QR Code WP — SPEC (v1.3.0 Appearance + Tabs)

## Goal
Two-tab settings screen. Tab 1 **Settings** = wallet library (unchanged). Tab 2
**Appearance** = visual controls with a **live preview**. QR size moves to Appearance.
Front-end styling already hardened to be theme-proof (self-scoped `.cqcw-block`,
button isolation, `margin:0 auto` QR centering) — verified on TT1 + TT5 (FSE).

## INVARIANTS
- Free, admin-only, no external HTTP. QR rendered client-side (davidshimjs).
- QR MUST encode the exact wallet address (verified by decode harness — NO regression).
- Single option `cqcw_settings` (array), Settings API, one nonce via `settings_fields()`.
- One `<form>` wraps both tabs → one Save persists everything.
- All output escaped; all input sanitized. Colors via `sanitize_hex_color`.
- Backward compatible: existing wallets + `qr_size` keep working; new keys default.

## STATE / DATA MODEL — `cqcw_settings` (flat)
| key          | type | default   | sanitize            | token / use |
|--------------|------|-----------|---------------------|-------------|
| wallets      | arr  | []        | per-row text fields | wallet rows |
| qr_size      | str  | '180'     | absint 80..400      | data-cqcw-size |
| qr_fg        | hex  | #000000   | sanitize_hex_color  | QR colorDark (data-cqcw-fg) |
| qr_bg        | hex  | #ffffff   | sanitize_hex_color  | QR colorLight (data-cqcw-bg) |
| tip_bg       | hex  | #ffffff   | sanitize_hex_color  | --cqcw-bg |
| tip_border   | hex  | #e5e5e5   | sanitize_hex_color  | --cqcw-border |
| tip_heading  | hex  | #686868   | sanitize_hex_color  | --cqcw-heading |
| addr_bg      | hex  | #e5e5e5   | sanitize_hex_color  | --cqcw-addr-bg |
| addr_text    | hex  | #444444   | sanitize_hex_color  | --cqcw-text |
| copy_bg      | hex  | #f3f3f3   | sanitize_hex_color  | --cqcw-copy-bg |
| copy_text    | hex  | #444444   | sanitize_hex_color  | --cqcw-copy-text |

## CORE ARCHITECTURE
- **Front-end tokens:** `wp_add_inline_style('crypto-qr-code-wp', '.cqcw-block{--cqcw-*:...}')`
  built from saved appearance. Prints only when shortcode enqueues the style.
- **QR colors:** shortcode emits `data-cqcw-fg` / `data-cqcw-bg`; `script.js` feeds
  them to `QRCode` as colorDark/colorLight (fallback #000/#fff).
- **Admin preview:** settings page also enqueues front `style.css` + `qrcode.min.js`
  + new `preview.js`. A sample open `.cqcw-block` lives in the Appearance tab; on any
  control `input`, preview.js rewrites the sample's inline `--cqcw-*` vars and
  re-renders its QR with current fg/bg/size. No save needed to preview.
- **Tabs:** `.nav-tab-wrapper` + two `.nav-tab`; `admin.js` toggles `.nav-tab-active`
  and shows/hides `#cqcw-tab-settings` / `#cqcw-tab-appearance` (hash-aware).

## ATTACK SURFACE
- Color injection into inline CSS → `sanitize_hex_color` (returns null on bad input → default). Never echo raw.
- qr_size out of range → clamp. data attrs `esc_attr`. Address already sanitized + `esc_attr`.
- Preview is admin-only (cap `manage_options`, page-hook gated enqueue).

## TECH DEPS
- WP 4.7+, PHP 7.4+. jQuery (admin). davidshimjs qrcode.min.js (bundled).

## TASK CHECKLIST
- [x] admin.php: defaults + sanitize for 9 color/size keys; enqueue style+qrcode+preview on page hook; pass appearance to view
- [x] crypto-qr-code-wp.php: wp_add_inline_style with tokens; bump 1.3.0
- [x] shortcode.php: read qr_fg/qr_bg → data attrs
- [x] script.js: use data-cqcw-fg / data-cqcw-bg
- [x] settings-page.php: nav tabs, two panels, appearance controls + preview markup, move qr_size
- [x] preview.js: live preview (tokens + QR re-render)
- [x] admin.css: tab + appearance grid + preview styling
- [x] admin.js: tab toggle
- [x] readme.txt / README.md / changelog.txt: 1.3.0 (incl. theme-proof + centering fixes)
- [x] Verify: php -l all; demo accuracy harness still 4/4; preview live on TT1
