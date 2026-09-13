---
title: "Pha 02 — Khả năng đọc Courses/DPM và heading semantic"
status: done
priority: P1
---

# Pha 02 — Khả năng đọc Courses/DPM và heading semantic

## Context links

Ba landing Course cùng tải `digital-performance.css`; nhiều helper/form/status/FAQ đang 10–13px. Home dùng H6 làm eyebrow; `/courses` bắt đầu bằng H6/H2 thay vì H1.

## Overview

P1, complete. Đã sửa đúng các surface Course/Home làm người dùng đọc hoặc điều hướng; giữ toàn bộ copy và contract hiện có.

## Requirements

- Sửa `public/site/assets/css/digital-performance.css`, `resources/views/home.blade.php`, `resources/views/courses.blade.php`.
- Không đổi câu chữ, section id, route helper, query, form, data CMS, payload hay logic fallback. Không sửa `article.css`; mọi selector article tiếp tục scoped.

## Architecture

`custom.css` phát token → stylesheet DPM chỉ consume token dưới `.dpm-page`; Blade chỉ đổi thẻ semantic và class eyebrow. Không có data flow hoặc route mới.

## Related files

- Modify: `public/site/assets/css/digital-performance.css`, `resources/views/home.blade.php`, `resources/views/courses.blade.php`.
- Read-only guard: `public/site/assets/css/article.css`, ba view `resources/views/courses/*` tải DPM CSS.
- Create/delete: none.

## Implementation steps

1. Xem DPM như asset legacy/minified: không format lại toàn file hay thay dây chuyền. Append một block override có nhãn ở cuối, toàn bộ selector dưới `.dpm-page`, để tạo diff reviewable và thắng cả các media rule 10–13px.
2. Dùng token Pha 1 để nâng mọi text có thông tin/chức năng lên ít nhất 14px: CTA/text link, hero/helper note, path/curriculum copy, caption có ý nghĩa, form intro/label/small/input, error/success, FAQ answer, closing/data note. Chỉ biểu đồ hoặc nhãn hoàn toàn trang trí mới được thấp hơn, phải không là control/form/help và có contrast được kiểm.
3. Giữ heading DPM, grid, ảnh, breakpoint và layout hiện hữu; bảo đảm mobile không ghi đè ngược helper/body về 10–13px. Kiểm tra cả `digital-performance`, `data-analysis`, `facebook-community` vì dùng chung CSS.
4. Đổi mọi H6 eyebrow của Home thành phần tử không-heading (`p` với class eyebrow semantic), giữ H2/id/aria-labelledby nguyên trạng. Không sửa H1 hero hoặc title CMS.
5. Trên `/courses`, đổi H6 thành eyebrow không-heading và H2 hiện tại thành H1; giữ nguyên text, thẻ H3 card/empty state và route/detail CTA.

## Todo

- [x] Append override DPM scoped cho role text 14px+ và mobile override tương ứng.
- [x] Đổi H6 eyebrow Home/Courses thành non-heading; đổi H2 listing thành H1.
- [ ] Visual-check ba landing Course, form state và hierarchy screen-reader (blocker môi trường ở Pha 03).

## Success criteria

- Outline screen-reader: Home chỉ có H1 rồi H2/H3 theo section; `/courses` có đúng một H1 trước H3 card.
- 320/375px và 200% zoom: DPM form, FAQ, course CTA, footer CTA/newsletter không che, không mất text, không tạo scroll ngang cấp trang.

## Risk and security

Rủi ro asset chung: QA đủ ba Course landing, dark/light và error/success trước khi chốt. Không thay request/form validation nên không thêm rủi ro bảo mật.

## Next steps

Pha 3 thêm regression assertions và quyết định dọn import bằng bằng chứng runtime.

## Trạng thái

DONE. Không migration, route hay thay đổi backend. Visual runtime được handoff ở Pha 03.
