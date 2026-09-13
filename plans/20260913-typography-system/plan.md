---
title: "Hệ thống typography public"
description: "Chuẩn hóa Be Vietnam Pro, khả năng đọc và focus mà không đổi route, CMS hay backend."
status: done-with-concerns
priority: P0
effort: 0.5d
branch: master
tags: [frontend, accessibility, typography]
created: 2026-09-13
blockedBy: []
blocks: []
---

# Kế hoạch — Hệ thống typography public

## Mục tiêu

Khắc phục focus P0, chuẩn chữ/cỡ chữ/contrast cho public bằng Be Vietnam Pro hiện có; giữ nguyên nội dung, URL, dữ liệu CMS, backend và style tổng thể.

## Quyết định và ràng buộc

- Be Vietnam Pro là font duy nhất cho text và display public, chỉ dùng weight đang tải `400/500/600/700`; không thêm font, dependency hay request weight.
- Không migration, route, controller, model, seed, API hoặc thay đổi markup dữ liệu. `article.css` vẫn chỉ selector dưới `.article-page`/`body.is-article`.
- Không sửa `main.css` lớn theo kiểu refactor. `master.blade.php` chỉ đổi nếu kiểm tra chứng minh link Be Vietnam Pro hiện hữu không đáp ứng yêu cầu.
- Legacy Kanit/Big Shoulders chỉ được xóa import sau khi mọi public route đã không còn render/chủ động dùng chúng; nếu chưa chứng minh được, ghi nợ kỹ thuật thay vì xóa liều.

## Phạm vi file

| Sửa | Điều kiện |
| --- | --- |
| `public/site/assets/css/custom.css` | Token public, contrast/focus và reflow CTA; nguồn chuẩn chính. |
| `public/site/assets/css/digital-performance.css` | Override `.dpm-page` hẹp cho text có thông tin/chức năng. |
| `public/site/assets/css/article.css` | Chỉ dùng token BVP cho eyebrow article và nâng metadata case study lên 14px; vẫn hoàn toàn scope `.article-page`. |
| `resources/views/home.blade.php`, `resources/views/courses.blade.php` | Chỉ sửa semantic heading/eyebrow, không đổi copy. |
| `resources/views/layouts/master.blade.php`, `public/site/assets/css/main.css`, `public/site/assets/scss/_typography.scss` | Chỉ khi audit font ở Pha 3 đạt điều kiện gỡ import. |
| `tests/Feature/PublicTypographySemanticsTest.php`, `docs/project-changelog.md` | Test semantic public và ghi nhận kết quả thực hiện. |

## Pha thực hiện

| Pha | Trạng thái | Kết quả |
| --- | --- | --- |
| [01 — Token, contrast, focus và reflow](./phase-01-public-tokens-focus-and-reflow.md) | Done | Token BVP, contrast light, focus và CTA reflow đã cài đặt. |
| [02 — Courses/DPM và semantic headings](./phase-02-course-readability-and-heading-semantics.md) | Done | Text chức năng >=14px; Home/Courses có outline heading đúng. |
| [03 — Test, visual QA và dọn font](./phase-03-accessibility-validation-and-font-cleanup.md) | Done with concerns | Static/regression test đã thêm; PHPUnit, visual và font-network còn blocker local. |

## Thành công / không thuộc phạm vi

- Tab focus thấy rõ ở link thường, CTA, input, select, button trong light/dark; light text token đạt AA theo vai trò.
- Text trợ giúp, form/status/error, FAQ và course copy đọc được từ 14px; CTA reflow tại 320px và 200% zoom, không clip/horizontal page scroll.
- Home không còn H6 chỉ để làm eyebrow; `/courses` có đúng một H1. Không redesign, không thay đổi SEO/data contract.
- Kiểm tra tĩnh đã pass. PHPUnit, visual/runtime và font-network phải xác nhận bằng browser/môi trường Laravel trước khi gỡ font legacy.

## Status protocol

Mỗi pha kết thúc bằng `DONE`, `DONE_WITH_CONCERNS`, `BLOCKED` hoặc `NEEDS_CONTEXT`, kèm tóm tắt, file ảnh hưởng, migration/route (đều phải là không), test đã chạy và câu hỏi còn lại.

## Trạng thái thực hiện

`DONE_WITH_CONCERNS`: đã cài đặt CSS/Blade/test, không migration/route/controller/model/data. `git diff --check` và kiểm tra cú pháp CSS tĩnh pass. Workspace thiếu `php` và `vendor/`, nên PHPUnit chưa chạy; local cũng không render được bản sửa để xác nhận 1440/768/375/320, zoom 200%, keyboard và light/dark. Kanit/Big Shoulders chưa bị gỡ import do chưa đạt gate runtime/network.
