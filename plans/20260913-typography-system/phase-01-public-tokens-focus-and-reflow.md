---
title: "Pha 01 — Token public, focus và reflow"
status: done
priority: P0
---

# Pha 01 — Token public, focus và reflow

## Context links

Nguồn: `plans/reports/scout-20260913-typography-system.md`, `plans/reports/uiux-20260913-typography-system.md`, `PROJECT-INSTRUCTIONS.md` §11. `main.css` xóa outline của anchor bằng `!important`; link xanh light hiện không đủ AA cho text thường.

## Overview

P0, complete. Đã tạo lớp token public ở cuối CSS để override legacy tối thiểu, không thay template/theme diện rộng.

## Requirements

- Sửa `public/site/assets/css/custom.css` trong pha này; thêm một block có nhãn ở cuối để thắng legacy mà không refactor template CSS lớn.
- Kiểm tra `resources/views/layouts/master.blade.php`: nó đã tải Be Vietnam Pro `400/500/600/700` và cache-busting CSS; không sửa khi điều đó còn đúng.
- Chỉ bổ sung hai selector `.article-page` vào `article.css`: eyebrow article dùng BVP và metadata case study có cỡ 14px. Không sửa route, Blade data, JS hay font dependency.

## Architecture

`main.css` legacy → block token cuối `custom.css` → token dark/light → body/heading/form/CTA public. `article.css` chỉ kế thừa custom properties khi cần, nhưng vẫn bị chặn trong `.article-page`/`body.is-article`.

## Related files

- Modify: `public/site/assets/css/custom.css`, `public/site/assets/css/article.css` (scope `.article-page` duy nhất).
- Read-only guard: `resources/views/layouts/master.blade.php`, `public/site/assets/css/main.css`.
- Create/delete: none.

## Implementation steps

1. Khai báo semantic custom properties: font `--font-sans`/`--font-display` đều Be Vietnam Pro; scale `--type-body` 16px, `--type-ui` 15–16px, `--type-meta` 14px, line-height body/heading và tracking eyebrow tối đa `.06em`.
2. Khai báo foreground theo vai trò `--text-strong`, `--text-default`, `--text-muted`, `--text-link`, `--focus-ring` cho dark/light. Dùng xanh đã kiểm `#4d7511` (hoặc giá trị tối hơn được đo lại) cho light text/link 14px+; giữ xanh sáng chỉ cho fill/border/large text.
3. Map body, heading public, navigation, form control, footer link, CTA và label sang token BVP; chữ tiếng Việt sentence case/zero tracking. Thay các effective `800` trên BVP bằng `700`, không yêu cầu synthetic weight.
4. Khôi phục focus keyboard bằng rule explicit `a:focus-visible` có mức ưu tiên tối thiểu đủ thắng reset legacy, cùng các control; ring 3px và offset 3px, không dùng color-only focus và không làm hỏng skip link/DPM focus riêng.
5. Sửa `.theme-btn` theo reflow: `min-height:48px`, `height:auto`, line-height ~1.35, wrap text, icon không co; cho nhóm newsletter/footer wrap/stack ở breakpoint hẹp thay vì clip. Giữ hover/animation và CTA layout cũ.

## Todo

- [x] Thêm token font, scale, foreground và focus cho hai theme.
- [x] Áp dụng BVP/token cho public text roles, focus và CTA reflow.
- [x] Kiểm tra cascade/focus tĩnh của Home, About, Blog, Courses và Contact.

## Success criteria

- Không còn font/size raw mới ngoài lớp token; không đổi kích thước body article hoặc phạm vi `.article-page`.
- Tĩnh: kiểm tra cascade `a:focus-visible` thắng `main.css`; không có `outline: none` mới. Browser: Tab qua skip link/header/CTA/form/footer ở hai theme.

## Risk and security

- Rủi ro CSS global: giới hạn selector public đã audit; browser-check Home/About/Blog/Courses/Contact trước Pha 2. Không có auth, input, secret hoặc dữ liệu mới.

## Next steps

Chuyển Pha 2 chỉ khi focus, light contrast và reflow nền tảng không có regression.

## Trạng thái

DONE. Không migration, route hay thay đổi backend. Visual/runtime còn ghi ở Pha 03.
