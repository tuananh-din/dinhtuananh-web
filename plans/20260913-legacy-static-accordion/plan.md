---
title: "Legacy static accordion accessibility"
description: "Repair interaction semantics for five public legacy static accordion pages without changing their unverified template copy or Laravel surface."
status: in_progress
priority: P1
effort: 5h
branch: master
tags: [bugfix, frontend, accessibility, legacy]
blockedBy: []
blocks: []
created: 2026-09-13
---

# Kế hoạch — Legacy static accordion

## Mục tiêu

Sửa interaction cho năm static URL công khai dưới `/site/`; không xác nhận, dịch, hoặc thay nội dung template tiếng Anh. Không đụng Laravel route/schema/model/controller.

## Kết quả audit và quyết định

- Cả năm URL trả HTTP 200 production: `/site/faq.html`, `/site/about.html`, `/site/index-2.html`, `/site/project-details.html`, `/site/service-details.html`.
- Mỗi file có cùng 5 trigger `<div class="acc-btn">`, một item mở, không focus/keyboard/state; `main.js` ép single-open bằng `slideUp/slideDown(300)`.
- `public/site` được `.cpanel.yml` deploy trực tiếp. Không có route/view Laravel tham chiếu năm file. Không có bằng chứng loại trừ file nào; **sửa cả năm**.
- Khuyến nghị **A — sửa semantics/interactions**: đổi từng item sang native `<details>/<summary>`, giữ nguyên toàn bộ text/panel/heading/image hiện có. Mở item đầu như hiện tại, các item còn lại đóng; cho phép multi-open để so sánh câu trả lời, không JavaScript/ARIA mirror/animation.
- **B — redirect/remove** chưa an toàn để tự làm: cần map từng URL tới đích canonical hoặc quyết định 404/410, kiểm tra backlink/traffic/indexing, và chủ nội dung duyệt. Không redirect chung về homepage, không xóa, không tạo/dịch copy trong plan này.

## Phạm vi

| Sửa | Không sửa |
| --- | --- |
| Năm HTML static, CSS/SCSS accordion dùng chung, bỏ handler jQuery đã không còn target, QA và changelog | Laravel routes/controllers/models/schema/migration; nội dung/translation; theme redesign; mapping SEO/redirect/removal; CMS content chưa audit |

## Files dự kiến

| Action | File |
| --- | --- |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/faq.html` |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/about.html` |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/index-2.html` |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/project-details.html` |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/service-details.html` |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/assets/css/main.css` |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/assets/scss/_feature.scss` |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/assets/js/main.js` |
| Modify after completion | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/docs/project-changelog.md` |

## Phases

| Phase | Status | Deliverable |
| --- | --- | --- |
| 01 — [Native semantic static accordion](./phase-01-native-static-accordion.md) | Complete | Five equivalent native disclosures; CSS contract; obsolete handler removed after source guard |
| 02 — [Static validation and release gate](./phase-02-static-validation-and-release-gate.md) | In progress — source validation complete; browser/deploy runtime QA pending | Browser/reduced-motion/accessibility QA, deploy smoke and factual changelog |

## Trạng thái hiện tại

- Repair markup/CSS/JS static và source validation đã hoàn tất cho cả năm URL.
- Chưa chạy browser, screen-reader, responsive/reduced-motion và deploy-runtime smoke QA; vì vậy chưa ghi nhận release hoàn tất hoặc changelog kết quả runtime.
- Không có migration, route, controller, model, backend hay schema Laravel nào thay đổi.

## Success criteria

- Every current header is a keyboard-operable `summary`, shows a decorative `+`/`−`, and exposes native collapsed/expanded state.
- First item only is initially open; later items start closed; opening one does not close another or move focus/scroll.
- Existing content bytes/meaning remain; no disabled/loading/error state is invented for static content.
- No page-level horizontal scroll, clipped question/icon or lost focus at 320/375/768/1440px and 200% zoom; reduced-motion introduces no disclosure animation.
- Migration: none. Routes/controllers/models/schema: unchanged.

## Dependencies and unresolved decision

The course-disclosure plan is related evidence but has no file/runtime dependency; it deliberately excludes these files. The static Sass source is not in the Laravel Vite input, so do not add a build pipeline: keep `_feature.scss` and deployed `main.css` synchronized using the existing asset process, if one is confirmed.

After the accessibility repair, the owner must decide whether these stale/unverified template URLs stay public, receive owner-approved replacement content, or get explicit SEO-safe redirects/removal. That is a separate content/legacy-URL phase.
