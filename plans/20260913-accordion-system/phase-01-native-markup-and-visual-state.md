# Phase 01 — Native markup and visual-state contract

## Context

- [Accordion source audit](../reports/scout-20260913-accordion-audit.md)
- [UX/a11y audit](../reports/uiux-20260913-accordion-system.md)
- [Plan overview](./plan.md)

## Overview

- Priority: P1
- Status: Complete
- Scope: the 33 active disclosures only. No backend/data migration, route, controller, model or JavaScript change.

## Requirements and design

1. Keep each native `details > summary` contract. A summary is its only trigger and contains no heading, link, button, form, `role`, or manual ARIA state.
2. In the three curriculum loops, replace only the nested `h3` with `span.dpm-module-title`; retain the existing number/title/description and first-loop `open` attribute.
3. In the analytics partial, change only the summary wording to `Xem bảng số liệu và cách tính CPL`; retain caption, table values, formula, horizontal scroll wrapper and its initially closed state.
4. In the scoped DPM stylesheet, transfer the old curriculum-title typographic/layout rules to `.dpm-module-title`, make it `flex:1; min-width:0`, allow wrapping, and keep the number/icon non-shrinking. Match the mobile size rule without truncation.
5. Give data-table summary a decorative CSS `+` when closed and `−` when `[open]`, plus the same non-color open surface/border treatment as the other disclosures. Do not add an announced icon or extra interactive markup.
6. Give curriculum, FAQ and data summary rows a subtle pointer hover and pressed surface/border treatment. Preserve the existing 3px `:focus-visible` outline, 64px minimum target and no custom height/slide transition. Open/hover/active must be valid in light and dark themes.

## Related code files

| Action | File | Responsibility |
| --- | --- | --- |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/courses/digital-performance.blade.php` | Valid curriculum summary span |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/courses/data-analysis.blade.php` | Valid curriculum summary span |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/courses/facebook-community.blade.php` | Valid curriculum summary span |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/partials/dpm-analytics.blade.php` | Accurate existing-data label only |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/assets/css/digital-performance.css` | Scoped states, icon and responsive title rules |

## Implementation steps

1. Confirm working tree; retain typography plan additions at the bottom of `digital-performance.css`.
2. Apply the same minimal span substitution to all three view loops. Do not reformat or rewrite unrelated one-line Blade sections.
3. Update the analytics label and CSS selectors/states. Keep marker suppression paired with an explicit replacement cue.
4. Inspect rendered source for the initial-state contract and absent `h1`–`h6` inside summaries.

## Todo

- [x] Replace the three curriculum headings with styled phrasing spans.
- [x] Clarify the data-table label without altering its real content.
- [x] Implement scoped default/open/hover/active/focus/responsive CSS states.
- [x] Static-check untouched routes, config data, layout and `main.js`.

## Success criteria

- Initial HTML: one open first curriculum item per landing; all FAQ/data details closed; no client script changes behavior.
- Icons and expanded state are visible without color alone; title wraps at narrow widths while number/icon remain visible.
- Enter/Space remain browser-native toggle controls and focus remains on summary.

## Risks and security

- Selector collisions with the typography append or `.dpm-page{overflow:hidden}` could clip content/focus or cause page overflow. Scope selectors, test 200% zoom, and retain table-local scroll.
- Native state must not be duplicated with ARIA/JS; doing so risks inconsistent announcements. No sensitive data, auth, input or server behavior is touched.

## Next step

Run phase 02 only after phase 01 source is reviewable.
