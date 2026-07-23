# E-07 — Xoá dead code `SocialController` + model `Social` (GIỮ bảng `socials`) (A11)

## Overview
- Priority: P2 | Status: Pending — **CHỜ USER DUYỆT (xoá module)** | Migration: KHÔNG (không drop bảng)

## Key Insights (đã verify)
- `app/Http/Controllers/Admin/SocialController.php` — chỉ có `updateSocial()` RỖNG (13 dòng), KHÔNG có route nào trỏ tới (đã đối chiếu toàn bộ `routes/web.php`).
- `app/Models/Social.php` — model trống, grep toàn bộ `app/` chỉ thấy chính nó + SocialController; không view/controller nào dùng.
- Bảng `socials` trong DB: 0 dòng (đã query). Social links thật dùng bảng `about` (sau E-05).
- AGENTS.md: "Không xoá module đã tồn tại chỉ để đúng scope hơn" + "xoá phải hỏi" → mục này BẮT BUỘC chờ user duyệt; đề xuất mức tối thiểu: chỉ xoá 2 file PHP, KHÔNG drop bảng `socials` (giữ khả năng revert, không cần migration).

## Requirements
- Sau khi xoá: app chạy bình thường, không lỗi class-not-found, route list không đổi.

## Related Code Files
- Xoá: `app/Http/Controllers/Admin/SocialController.php`, `app/Models/Social.php`.
- Sửa/Tạo: KHÔNG. KHÔNG tạo migration drop `socials`.

## Implementation Steps
1. **Chờ user duyệt.**
2. Grep xác nhận lần cuối không còn reference: `Grep "Social" app/ routes/ resources/ database/seeders/` (bỏ qua chính 2 file sẽ xoá; lưu ý không match nhầm "SocialController" trong import nào khác).
3. Xoá 2 file. Chạy `composer dump-autoload` (tránh autoload map cũ).
4. `php artisan route:list` chạy OK, không lỗi.

## Todo
- [ ] User duyệt xoá
- [ ] Grep xác nhận không còn reference
- [ ] Xoá 2 file + dump-autoload
- [ ] Test tay theo Success Criteria
- [ ] Commit: `refactor: xoa dead code SocialController va model Social`

## Success Criteria (test tay)
1. `php artisan route:list` không lỗi; số route không đổi so với trước.
2. Duyệt nhanh: `/`, `/about`, `/admin`, `/admin/profile` — không lỗi 500.
3. Bảng `socials` vẫn còn trong DB (`SHOW TABLES`).

## Risk Assessment
- Rất thấp: 0 route, 0 usage, 0 data. Rollback: `git revert` (file trong git history).

## Security Considerations
- Giảm attack surface (bớt controller không dùng). Không đổi auth/route.

## Next Steps
- Làm CUỐI Phase E. Nếu tương lai cần module social riêng → tạo mới theo yêu cầu thật (YAGNI).
