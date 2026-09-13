---
title: "Giảm jank cuộn trên mobile public site"
description: "Ưu tiên native scroll cho touch/reduced-motion, giảm scroll work và chỉ nạp hiệu ứng đã có DOM sử dụng."
status: in_progress
priority: P1
effort: 1.5d
branch: master
tags: [bugfix, frontend, performance, accessibility]
blockedBy: []
blocks: []
created: 2026-09-13
---

# Kế hoạch — Mobile Scroll Performance

## Mục tiêu

- Loại nguồn jank có căn cứ mạnh nhất: `ScrollSmoother` chạy trên homepage touch/mobile.
- Dùng một policy capability cho **mọi** initializer GSAP/ScrollTrigger/SplitText; coarse/reduced giữ trạng thái raw thấy được.
- Giảm JavaScript chạy theo scroll, animation không cần thiết và vendor nạp global.
- Giữ ý đồ visual desktop; giữ native scroll hiện có của Article/DPM.
- Chỉ thay đổi frontend public. Không migration, route, schema, model, controller nghiệp vụ, package hay redesign.

## Không thuộc phạm vi

- Không hứa FPS, LCP hay điểm Lighthouse khi chưa có trace tương đương.
- Không đổi content/CMS, deploy, xoá asset cũ, backfill/derivative ảnh, hay sửa `code_header`/`code_footer` trong DB.
- Responsive hero image là follow-up không chặn sau khi owner media xác nhận lifecycle upload/rollback; không thuộc ownership của kế hoạch này.
- Không bulk thêm `defer`: thứ tự script và `@stack('scripts')` hiện phụ thuộc jQuery/main.js.

## Evidence nguồn

- `resources/views/layouts/master.blade.php:86-175` nạp CSS/JS global, không `defer`/`async`; luôn render smooth shell tại `:119-126`.
- `public/site/assets/js/main.js:738-803` tạo `ScrollSmoother` cho mọi page trừ `is-article`/`is-dpm`, gồm homepage; `smoothTouch: 0.1` không có gate mobile.
- `main.js:89-95`, `:707-717`, `:126`, `:1237-1305`, `:1422-1435` lần lượt là two scroll handlers, WOW, Swiper unguarded, SplitText scrub.
- `resources/views/home.blade.php:39,355-393`: hero chỉ một `src`; typing interval lặp vô hạn. Production hero PNG đã đo 1785×2560, 1,524,403 bytes.
- Live `/` HTTP 200; HTML nén 11,260 bytes. `code_header`/`code_footer` có thể thêm runtime không thấy trong source. Puppeteer trace không dùng được vì thiếu dependency.

## Quyết định thiết kế

| ID | Quyết định | Lý do |
| --- | --- | --- |
| D1 | Coarse-pointer, no-hover hoặc reduced-motion dùng native scroll và không tạo GSAP/ScrollTrigger/SplitText app effects. | Không UA sniff; đáp ứng evidence ScrollSmoother touch và raw state an toàn. |
| D2 | Fine-pointer desktop giữ enhanced scroll trước; thay đổi chỉ khi QA chứng minh regression. | Bảo toàn visual intent desktop. |
| D3 | Guard/init theo DOM và capability trước, rồi mới gỡ/tách vendor đã xác minh. | Không phá dependency script/legacy page. |
| D4 | Responsive hero image là follow-up decision riêng, non-blocking. | Pipeline hiện lưu một URL; không tạo derivative/backfill trong plan này. |

## Phases và phụ thuộc

| Phase | Deliverable | Depends on | File ownership |
| --- | --- | --- | --- |
| [01 — Baseline và runtime contract](./phase-01-baseline-and-runtime-contract.md) | Evidence so sánh, inventory runtime và full route matrix | — | QA only; không sửa code |
| [02 — Native mobile scroll và motion safety](./phase-02-native-mobile-scroll-and-motion-safety.md) | Capability gate cho toàn bộ GSAP-family; contract reduced-motion | 01 | `main.js` GSAP-family sections; reduced-motion contract |
| [03 — Giảm scroll work và vendor global](./phase-03-event-and-asset-loading-reduction.md) | Một scroll pipeline, guarded features, vendor/order contract | 02 | `master.blade.php`, `main.js` non-smoother sections, view stacks |
| [04 — Hero typing lifecycle và release QA](./phase-04-hero-lifecycle-media-and-release-qa.md) | Typing lifecycle và regression proof | 03 | `home.blade.php`, focused public markup test |

Phases chạy tuần tự vì Phase 02/03 cùng chạm `main.js`; không giao song song file đó.

## Thành công toàn kế hoạch

- Mobile/reduced-motion không tạo ScrollSmoother, ScrollTrigger hay SplitText app effect; raw text/image/CSS state vẫn thấy và điều hướng/form vẫn hoạt động.
- Chỉ còn một pipeline scroll native cho header/back-to-top; không còn Swiper constructor unguarded.
- Mọi CSS/JS asset bỏ/tách có inventory DOM, bare-global guard và thứ tự dependency xác minh; thiếu bất kỳ điều kiện nào là release blocker.
- Article/DPM vẫn native; không thay route, schema, migration, dependency hoặc dữ liệu production.

## Tiến độ triển khai — 2026-09-13

- Đã áp dụng capability gate, native scroll pipeline, plugin guards và lifecycle typing theo các phase 02–04.
- Đã thêm source-contract test và static checks. PHP CLI, `vendor/` và local runtime không có trong workspace, nên PHPUnit/visual/device matrix vẫn là bước bắt buộc trước release.
- Giữ nguyên toàn bộ vendor tags: chưa có rendered inventory cho dynamic `code_header`/`code_footer`, nên không tách hoặc xóa asset theo đúng contract an toàn.

## Rollback

- Frontend-only, không migration: revert commit, deploy lại, rồi `php artisan view:clear` theo quy trình cPanel.
- Không có data/media rollback vì plan này không thay đổi upload hay derivative.
