---
title: Typography and accessibility UX audit — public personal brand
date: 2026-09-13
scope: Read-only — Home, About, Blog, Courses, shared header/footer
status: complete-with-visual-testing-blockers
---

# Typography and accessibility UX audit

## Outcome

Keep **Be Vietnam Pro** as the sole public UI/content face. The article detail already shows the intended direction: scoped tokens, 17–19px reading text, a controlled measure, natural Vietnamese casing, and explicit inline-link treatment. The rest of the public site still inherits the legacy template’s Kanit/Big Shoulders/all-caps rules, so its type hierarchy varies by page rather than by semantic role.

No source code, content, routes, data, or dependencies changed in this audit.

## Highest-value findings

| Priority | Finding | Evidence | Implementable direction |
| --- | --- | --- |
| P0 | Keyboard focus on ordinary links is not reliably visible. | [`main.css`](../../public/site/assets/css/main.css#L191) sets `a { outline: none !important; }`; the later global focus rule in [`custom.css`](../../public/site/assets/css/custom.css#L783) is not important, so it cannot restore an outline on anchors. | Remove the legacy important reset, or define `a:focus-visible` with the minimum needed precedence. Keep 3px ring + 3px offset; test header, all CTA links, cards, footer links, light and dark. |
| P0 | Light-mode brand green fails normal-text AA when it is used as a text/link color. | `--theme: #5f8f16` in [`custom.css`](../../public/site/assets/css/custom.css#L811) yields 3.87:1 on white / 3.61:1 on the page off-white. It is used for active navigation and eyebrows. | Use `#4d7511` (already used by article/light text links) or a darker verified token for any 14px+ text link/label. Reserve the brighter green for fills, borders, or large text. |
| P1 | Three competing public font systems undermine Vietnamese hierarchy. | Legacy imports and body/headline assignment: [`main.css`](../../public/site/assets/css/main.css#L22), [`main.css`](../../public/site/assets/css/main.css#L44), [`main.css`](../../public/site/assets/css/main.css#L83). Be Vietnam Pro is loaded in [`master.blade.php`](../../resources/views/layouts/master.blade.php#L106), while only article headings explicitly reset it. | Map body, headings, navigation, buttons, inputs, and footer to Be Vietnam Pro. Do not add a font or request weight. Search before deleting legacy font imports; retain a display face only if there is an explicit approved brand need. |
| P1 | Current 12–13px text is used beyond purely decorative metadata. | Examples: course badge 12px and lead note 13px in [`custom.css`](../../public/site/assets/css/custom.css#L544); About eyebrow/card label 12px at [`custom.css`](../../public/site/assets/css/custom.css#L2172); article case labels 13px at [`article.css`](../../public/site/assets/css/article.css#L206). | Set 14px as the normal minimum for labels, dates, helper/error/status text, filters, and action labels. Keep 12px only for non-essential visual eyebrow/badge content, with 1.4+ line-height and verified contrast. |
| P1 | Long CTA labels can clip rather than reflow at narrow widths / 200% zoom. | `.theme-btn` has fixed `height` and matching `line-height` (58px → 52px) at [`main.css`](../../public/site/assets/css/main.css#L208); footer has a long CTA and an inline email/button row at [`footer.blade.php`](../../resources/views/layouts/footer.blade.php#L24). | Make action controls `min-height:48px; height:auto; line-height:1.35; display:inline-flex; align-items:center; white-space:normal`. Stack/wrap the footer email controls below a practical width. |
| P2 | The scale is non-systemic. Blog/About are good page-local scales, Courses still largely receive legacy global headings. | Article has local semantic tokens at [`article.css`](../../public/site/assets/css/article.css#L29); Blog and About use strong local clamps at [`custom.css`](../../public/site/assets/css/custom.css#L1811) and [`custom.css`](../../public/site/assets/css/custom.css#L2181); global headings remain Big Shoulders/all-caps. | Add one public type-token layer in `custom.css`; migrate scopes progressively. Do not globalize article selectors or weaken its `.article-page` boundary. |

## Recommended semantic type tokens

Use current loaded weights only: **400 / 500 / 600 / 700**. Existing `800` declarations on inherited Be Vietnam Pro text should become `700`, avoiding synthetic bold. Vietnamese body and UI text should use normal casing and zero tracking; brief uppercase labels are the only exception.

| Role | Size | Weight / leading / tracking | Use |
| --- | --- | --- | --- |
| `font-sans` | `"Be Vietnam Pro", system-ui, sans-serif` | — | Every public text role, including nav and CTA |
| `display` | `clamp(38px, 6vw, 80px)` | 700 / 1.08 / `-.02em` | One prominent page hero only |
| `page-title` | `clamp(36px, 4.5vw, 72px)` | 700 / 1.10 / `-.015em` | Standard H1 |
| `section-title` | `clamp(30px, 3.2vw, 48px)` | 700 / 1.16 / `-.01em` | H2 |
| `card-title` | `clamp(22px, 2vw, 28px)` | 600–700 / 1.30 / `0` | H3 / H4 and course/blog cards |
| `body-reading` | `clamp(17px, 1.2vw, 19px)` | 400 / 1.70 / `0` | Long-form article only; preserves current article quality |
| `body` | `16px` | 400 / 1.65–1.70 / `0` | Standard page, list, and card copy |
| `body-lead` | `18px` desktop, `16px` mobile | 400 / 1.65–1.70 / `0` | Intro/supporting paragraph |
| `ui-action` | `15–16px` | 600 / 1.35 / `0` | Buttons, nav, filter action |
| `meta` | `14px` | 500 / 1.45 / `0` | Date, price context, helper, status |
| `eyebrow` | `14px` | 600–700 / 1.4 / max `.06em` | Optional short label; prefer sentence case |

Additional implementation rules:

- Keep prose/paragraph links visibly underlined (`text-underline-offset: .16em`); navigation and button links may use their component affordance instead. The article pattern at [`article.css`](../../public/site/assets/css/article.css#L503) is the reference.
- Use `font-variant-numeric: tabular-nums` for dates, price, and other scanable numbers.
- Keep article reading measure at roughly 68–72 characters (current 700–740px configuration is appropriate); cap non-article body/lead blocks at 65–70ch.
- Define semantic foreground tokens for both modes: `text-strong`, `text-default`, `text-muted`, `text-link`, `focus-ring`. Avoid deriving meaning from `--body`, `--header`, or template-specific colors.

## Responsive and zoom assessment

This table is source-based, not a rendered-pass result; see blockers below.

| View | What is already sound | Type risk / acceptance condition |
| --- | --- | --- |
| 1440 | Article layout locks body measure to 740px with a separate 280px table of contents; Blog and About hero scales are bounded with `clamp()`. | Global Home/Courses headings can still use the legacy display face/all-caps, so the same role presents differently by route. Apply the common roles above. |
| 768 | About audience content stacks at <=991px; Blog filter controls become a three-area layout and have a one-column fallback below 768px. | Verify the exact 768px transition with long Vietnamese labels and course names; no text may overlap CTA or filter controls. |
| 375 | Blog filters and Blog CTA stack; About actions become full-width stacked controls. | `theme-btn` is 14px and fixed-height at <=575px. UI/action text should be at least 15px and permit two-line reflow. |
| 320 | Article body becomes 17px with 16px gutters; content tables intentionally scroll inside their container. | Check all legacy Home/Courses CTA strings, footer email form, card titles, and mobile navigation for clipping/horizontal loss. `overflow-x:hidden` must not hide an underlying layout overflow. |
| 200% zoom | The viewport meta allows zoom, and breakpoint-based layouts should activate. | Zoom turns 1440px into an effective ~720px layout and 768px into ~384px. Acceptance: no clipped CTA text, no lost footer form control, and focus ring remains wholly visible; reflow rather than horizontal page scrolling. |

## Light/dark and link detail

- Dark body text `#888` on `#060606` is 5.72:1; dark accent `#bff747` is 16.03:1. Both are safe as currently calculated.
- Light `#6b7280` on `#f6f7f9` is only 4.51:1; it technically passes AA but has little margin. Use a darker muted-text token where text is 14px or important.
- Light placeholder `#9aa0a8` on white is 2.64:1. Do not use placeholder as the only form label; the site’s visible labels are a good existing pattern.
- The skip link is present and visibly reveals on focus ([`master.blade.php`](../../resources/views/layouts/master.blade.php#L116), [`custom.css`](../../public/site/assets/css/custom.css#L768)). Preserve it. The remaining work is to make ordinary link focus equally dependable.

## Keep / avoid

Keep the scoped article system: natural Vietnamese casing, BVP headings/body, body 19px desktop / 17px mobile, restored list markers, and real inline-link underlines ([`article.css`](../../public/site/assets/css/article.css#L414)). It is the right internal implementation model.

Avoid a broad redesign, a new font dependency, raw per-component font values, all-caps long Vietnamese headings/CTA labels, and any focus fix that relies only on a color change.

## Visual-testing blockers

- `curl` to `https://dinhtuananh.com/`, `/about`, `/blog`, and `/courses` returned HTTP `000` in this environment; the live public pages could not be reached.
- The available Puppeteer audit script could not run because its `puppeteer` package is absent. Therefore no screenshots, computed styles, keyboard traversal, or actual 1440/768/375/320/200% zoom rendering could be claimed.
- Findings above are from the current local Blade/CSS cascade and calculated color contrast. Before implementation sign-off, run the manual checks in a reachable browser using current production-equivalent data.

## Unresolved questions

- Is Big Shoulders Display an explicitly approved headline brand face for Vietnamese, or should Be Vietnam Pro own all public headings? This decides whether the existing legacy font imports can be removed after selector cleanup.
- Which current course landing(s) should be considered in the first visual regression pass? They load an additional dedicated stylesheet and were not render-verified here.

**Status:** DONE_WITH_CONCERNS
**Summary:** Completed a read-only typography/accessibility audit and defined a Be Vietnam Pro-only semantic role system plus priority fixes.
**Concerns/Blockers:** Live site/network access and local browser automation were unavailable, so responsive, zoom, focus, and theme findings are source-based rather than screenshot-verified.
