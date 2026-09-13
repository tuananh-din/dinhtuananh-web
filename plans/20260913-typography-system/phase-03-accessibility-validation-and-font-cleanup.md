---
title: "Pha 03 — Validation accessibility, regression và dọn font"
status: done-with-concerns
priority: P0
---

# Pha 03 — Validation accessibility, regression và dọn font

## Context links

Nguồn validation: hai audit typography, `PROJECT-INSTRUCTIONS.md` §8/§11, cache-busting trong `resources/views/layouts/master.blade.php` và test public hiện có.

## Overview

P0, implementation complete with validation concerns. Không gọi source audit là visual pass.

## Requirements and related files

- Thêm `tests/Feature/PublicTypographySemanticsTest.php` để giữ markup Home có eyebrow không-heading và `/courses` có eyebrow không-heading, một H1.
- Sửa `docs/project-changelog.md` sau khi implementation/test hoàn tất. Chỉ khi đủ điều kiện mới sửa `main.css`, `_typography.scss` và/hoặc `master.blade.php` để gỡ font legacy.
- Create/delete: none; không migration, route hay backend change.

## Architecture

PHP feature tests bảo vệ HTML semantics; static inspection bảo vệ cascade/import; browser bảo vệ computed font, focus, contrast và reflow. Gỡ import là bước cuối, đảo được qua git nếu regression.

## Implementation steps

1. Thêm assertion HTML semantic, không snapshot CSS mong manh; chạy targeted test mới rồi `php artisan test` bằng PHP Laragon đã xác minh trong `PROJECT-INSTRUCTIONS.md` §8.
2. Kiểm tra Blade/CSS syntax, `git diff --check`, và search Kanit/Big Shoulders/import ở `master.blade.php`, `main.css`, SCSS và public views. Xác nhận master vẫn chỉ request BVP weights 400/500/600/700.
3. Dùng browser thật ở 1440/768/375/320px và 200% zoom: Home, About, Blog listing/detail, Courses listing/detail, ba DPM landing, Contact; light/dark, keyboard-only, long Vietnamese CTA, error/success form và reduced motion.
4. Ghi rõ giới hạn nếu browser/runtime unavailable; không tuyên bố visual pass dựa trên source audit.

## Legacy-font cleanup gate

1. Trước hết audit computed font trên các route public và static selector inventory: mọi text/display user-facing phải resolve BVP/system fallback, không còn selector active cần Kanit/Big Shoulders.
2. Chỉ khi audit và network xác nhận, xóa import legacy từ `main.css` và nguồn SCSS tương ứng; nếu source SCSS là đường build đang dùng, build lại asset đúng quy trình. Không gỡ Be Vietnam Pro link, không thêm weight/host mới, giữ cache-busting.
3. Lặp network/computed-style/visual regression sau xóa: không còn request legacy font, không fallback/synthetic bold và không lỗi admin/template. Nếu một usage chưa được thay an toàn, giữ import, nêu blocker và mở task hẹp tiếp theo thay vì broaden scope.

## Todo

- [x] Thêm assertion semantic vào test public mới.
- [x] Chạy `git diff --check` và static CSS checks; targeted/full PHPUnit bị blocker `php`/`vendor`.
- [ ] Hoàn tất browser/network matrix và chỉ sau đó quyết định xóa/giữ import.
- [x] Ghi changelog theo thay đổi thực tế và handoff rõ giới hạn.

## Success criteria

- Cập nhật changelog bằng phạm vi thực tế, test đã chạy và hạn chế runtime nếu có; không cập nhật roadmap/migration vì đây không đổi phase backend.
- Handoff nêu file sửa/tạo, route/migration = không, manual QA, rủi ro còn lại và trạng thái chuẩn.

## Risk and security

Browser/runtime không có sẵn là blocker cho cleanup import, không phải lý do bỏ QA. Không có input, authorization, secret hoặc dữ liệu khách hàng mới.

## Next steps

Sau `DONE`, review code sạch rồi commit một thay đổi CSS/Blade/test/doc; nếu import bị giữ, mở task hẹp cho selector còn active.

## Trạng thái

DONE_WITH_CONCERNS. Static QA pass; không có PHP CLI hoặc `vendor/` nên chưa chạy PHPUnit. Bản sửa không render local nên chưa thể xác nhận viewport, zoom, focus thực tế hoặc network font. Legacy imports được giữ đúng gate.
