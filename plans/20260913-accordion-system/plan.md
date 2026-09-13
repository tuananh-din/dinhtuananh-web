---
title: "Native course disclosure accessibility"
description: "Repair semantics and visible state for the active native disclosures on the three DPM course landing pages."
status: code-complete-runtime-qa-pending
priority: P1
effort: 4h
branch: master
tags: [feature, frontend, accessibility, blade]
blockedBy: []
blocks: []
created: 2026-09-13
---

# Kế hoạch — Native course disclosures

## Mục tiêu

Hoàn thiện semantics, trạng thái nhìn thấy và responsive của 33 `<details>/<summary>` đang dùng thật trên ba landing course. Giữ nội dung, route, CMS/schema/model, contract lead và hành vi native của trình duyệt.

## Quyết định đã chốt

- Giữ `<details>/<summary>` native; không dùng Bootstrap Collapse, không thêm JavaScript, `aria-expanded`, `aria-controls`, `role=button` hoặc animation.
- Curriculum: chỉ buổi đầu mở khi tải; **multi-open**. Học viên cần đối chiếu nhiều buổi mà không mất phần đang đọc.
- FAQ và bảng dữ liệu: đóng khi tải; **multi-open**, độc lập nhau. Cần so sánh câu trả lời/số liệu, không tự đóng mục khác hay đổi focus/scroll.
- Curriculum thay `h3` trong `summary` bằng `span.dpm-module-title`; giữ nguyên số buổi, title và mô tả từ config. Data disclosure đổi chính xác thành `Xem bảng số liệu và cách tính CPL`.
- CSS scoped `.dpm-page` bổ sung cue `+`/`−`, open surface/border, hover/active bổ trợ, focus hiện có, wrapping title (`min-width:0`) và target tối thiểu 64px. Không dùng color-only state; không làm page ngang-scroll.
- Không sửa `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/assets/js/main.js`, layout, route/controller/config/model/schema, static legacy HTML hoặc nội dung CMS.

## Phạm vi và ranh giới

| In scope | Out of scope / quyết định cần duyệt |
| --- | --- |
| Ba view active, partial bảng dữ liệu, CSS DPM và ba feature test hiện có | Legacy `/site/about.html`, `/site/faq.html`, `/site/index-2.html`, `/site/project-details.html`, `/site/service-details.html`; `main.js` legacy accordion; redirect/removal/translation/copy mới |

Ba trang trong scope là landing Laravel do `CourseController` chọn view: `/courses/digital-performance-management`, `/courses/facebook-community-growth-system` và `/courses/data-analysis-visualization`. Các đường dẫn `/site/*.html` là static file trong `public/site`, không phải Laravel route/view; chúng chỉ được nêu như rủi ro public cần scope riêng.

`curl -I` đã xác nhận ít nhất `/site/faq.html`, `/site/about.html`, `/site/index-2.html` trả 200 production. Đây là P0 accessibility/content exposure thực tế nhưng là hệ thống jQuery/div riêng, single-open và có nội dung template không xác minh. Không sửa theo plan này: chủ site phải duyệt một scope riêng (giữ và rebuild native bằng nội dung đã duyệt, hoặc redirect/remove bề mặt public). Không được tự tạo tiếng Việt, xóa trang hay redirect.

## Phases

| Phase | Status | Deliverable |
| --- | --- | --- |
| 01 — [Native markup and visual-state contract](./phase-01-native-markup-and-visual-state.md) | Complete | Semantics và states nhất quán cho active disclosures |
| 02 — [Regression, browser QA and docs handoff](./phase-02-regression-browser-qa-and-docs.md) | Static complete; runtime QA pending | Coverage render, manual accessibility QA, changelog đúng thực tế |

## Affected files

| Action | File |
| --- | --- |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/courses/digital-performance.blade.php` |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/courses/data-analysis.blade.php` |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/courses/facebook-community.blade.php` |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/partials/dpm-analytics.blade.php` |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/assets/css/digital-performance.css` |
| Modify | Existing landing tests named in phase 02; no new routes, schema, models, scripts, or test doubles |

## Dependencies and conflict control

- No functional cross-plan blocker found. `20260913-typography-system` is not complete and also scopes `digital-performance.css`; before implementation, preserve its final scoped typography overrides and rebase/review the current CSS diff rather than overwrite it.
- Existing audit reports under `plans/reports/` are untracked work from another task; preserve them unchanged.
- `vendor/` and a PHP CLI are absent here. Do not claim PHPUnit or browser QA passed until run in Laragon/full Laravel environment.

## Success criteria

- Every active summary has meaningful existing text, visible `+`/`−`, non-color open cue, hover/active/focus state, keyboard-native operation and responsive wrapping.
- Only first curriculum details is initially `open`; FAQ/data are closed; opening any active item never closes another.
- No heading or nested interactive content occurs inside `summary`; content, routes and data contracts are unchanged.
- Static/render checks, three targeted tests, full suite, and manual light/dark/responsive/keyboard checks are recorded honestly.

## Docs impact

Minor after implementation only: append a factual entry to `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/docs/project-changelog.md`. No roadmap, architecture, deployment or README update unless the approved scope changes.
