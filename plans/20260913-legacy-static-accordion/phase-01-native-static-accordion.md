# Pha 01 — Native semantic static accordion

## Context

- [Accordion source audit](../reports/scout-20260913-accordion-audit.md)
- [UX/a11y audit](../reports/uiux-20260913-accordion-system.md)
- [Kế hoạch hiện tại](./plan.md)

## Overview

Priority P1. **Hoàn tất.** Đã thay interaction click-only ở đúng năm template static bằng disclosure native, không sửa nội dung hoặc Laravel. Migration: none; route/backend/schema không đổi.

## Key insights

- Năm block giống nhau: 5 question/answer, item đầu đang `active/current`; CSS chia hai layout `.faq-items` và `.faq-inner-page-wrapper`.
- `main.js` là handler duy nhất cho `.acc-btn`; source không có HTML static nào khác dùng classes này. `custom.css` có một font-size selector legacy nhưng các static page không tải file đó.
- `main.css` là asset deploy; `_feature.scss` là source tương ứng nhưng Laravel Vite chỉ build `resources/css/app.css` và `resources/js/app.js`.

## Requirements and interaction contract

- Giữ nguyên exact text hiện có, thứ tự 5 item, heading, ảnh và layout wrapper. Không thay, dịch hay suy diễn câu trả lời template.
- Mỗi `li` giữ animation/layout hiện có nhưng chứa `<details class="legacy-disclosure">`; `summary` là child đầu, theo sau là panel content đang có. Icon là span trang trí `aria-hidden="true"`; không nested link/button/form trong summary.
- Item đầu mang `open`; bốn item còn lại không. Chọn **multi-open**: native details độc lập, không auto-close, auto-scroll hay chuyển focus. Đây là thay đổi có chủ ý từ single-open để FAQ so sánh được và loại JavaScript tùy biến.
- Không thêm `role=button`, `aria-expanded`, `aria-controls`, id panel hay state mirroring. Native disclosure đã cung cấp keyboard/state.
- Không có disabled/loading/error state: static FAQ không có điều kiện khiến một item không sẵn sàng. Không thêm `aria-disabled` hoặc UI giả.

## Related files

| Action | Files | Ownership |
| --- | --- | --- |
| Modify | `public/site/faq.html`, `public/site/about.html`, `public/site/index-2.html`, `public/site/project-details.html`, `public/site/service-details.html` | Markup native, no copy edits |
| Modify | `public/site/assets/scss/_feature.scss`, `public/site/assets/css/main.css` | Matching disclosure styles/source and deployed CSS |
| Modify conditionally after source guard | `public/site/assets/js/main.js` | Remove only obsolete `.accordion-box` click/slide handler |

## Implementation steps

1. Snapshot the five existing accordion subtrees. Convert each with the same element/class contract, preserving its current question/answer strings and `wow` delay classes. Remove `.acc-btn`, `.acc-content`, `.active`, `.current`, and `.active-block` only from those five blocks.
2. Replace the two duplicated old-selector CSS branches with scoped styles for `legacy-disclosure`: reset native marker, make the full summary a flex/wrapping target of at least 44px, retain its context-specific desktop type scale, and keep panel spacing/readability.
3. Style an `aria-hidden` icon as `+` collapsed and `−` when `[open]`; pair state with border/surface or text change, not color alone. Add hover and active affordances plus a visible `:focus-visible` outline that works despite old global outline resets. At narrow widths reserve icon space, allow question wrapping, and avoid page-level overflow.
4. Do not animate open/close. Limit any hover transition and disable it in `@media (prefers-reduced-motion: reduce)`. Keep the responsive type/spacing rules already needed by both wrapper contexts.
5. Update `_feature.scss` and generated/deployed `main.css` with equivalent scoped changes. Do not add Vite, package scripts, or a new CSS pipeline. If an existing legacy Sass build command is later confirmed, run it and review that the resulting CSS diff is limited to this component.
6. Before editing `main.js`, run a source guard over static HTML for `.acc-btn`, `.acc-content`, `.active-block`, and old `.accordion-box` usage. If it confirms the five converted pages were the only handler targets, delete only the 24-line click/`slideUp`/`slideDown` block. Preserve all other jQuery behavior. If a new target is found, stop and report it rather than silently breaking it.

## Success criteria

- Each of five pages has five native details, exactly one initial `open`, five meaningful summaries and five unchanged content panels.
- Tab, Enter and Space work without custom JS; native state is announced; icon is not announced.
- Header, icon, content, hover, pressed, focus, collapsed/open and responsive states are explicit. No disabled policy is simulated.
- No Laravel file, route, controller, model, schema or migration changes.

## Risks and mitigation

- Native marker/layout can differ by browser: neutralize markers in CSS, test Chromium/Safari/Firefox where available.
- Copy is visibly stale/unverified: preserve it now; do not mistake this a11y repair for content approval.
- SCSS/CSS may drift: make both edits in the same review and compare their selectors; do not rely on Vite.

## Todo

- [x] Convert five copied accordions to native disclosures without copy changes.
- [x] Synchronize source/deployed styles and remove the handler after source guard passed.
