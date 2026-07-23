# E-05 — Migration thêm 4 cột social vào `about` (sửa bug 500 lưu profile)

## Overview
- Priority: **P0** (bug thật đang chặn lưu profile) | Status: Pending — **CHỜ USER DUYỆT SCHEMA**
- Migration: **CÓ** (thêm 4 cột nullable, không đụng data cũ)

## Key Insights (đã verify)
- BUG: form `resources/views/admin/profile.blade.php` có input `facebook` (dòng 107), `instagram` (120), `x` (134), `linkedin` (148). `AboutController::updateProfile` dòng 40-42 đưa cả 4 vào `$request->only([...])` → `About::updateOrCreate` (model `$guarded = []`).
- Bảng `about` trong DB `tuanh` KHÔNG có 4 cột này (đã `SHOW COLUMNS` — chỉ có name/avatar/image/description/content/about_me/tel/email/address/*_seo). → Mỗi lần bấm lưu profile: `QueryException: Unknown column 'x'` → lỗi 500. Bằng chứng phụ: `about` row id=1 có `tel/email = NULL` — form chưa từng lưu thành công.
- Hệ quả kéo theo: social links đã code sẵn nhưng chết ở `layouts/header.blade.php:61-64`, `layouts/footer.blade.php:27-56`, `home.blade.php:34-37` (đều đọc `$about->facebook`... qua composer `$contact`) — có cột + có data là tự hiển thị, KHÔNG cần sửa view.
- Migration nguồn `2026_01_05_084900_create_database_table.php` tạo `about` không có social — thiếu từ đầu, không phải drift.

## Requirements
- Lưu admin profile thành công (không 500), 4 link social lưu và hiển thị lại đúng trong form.
- Social links tự hiện ở header/footer/home khi có data.
- KHÔNG mất/đổi data hiện có của bảng `about`.

## Phương án (user chọn)
- **A (khuyến nghị):** migration mới `add_social_columns_to_about_table` thêm `facebook`, `instagram`, `linkedin`, `x` — `string(191) nullable` sau cột `address`. Đúng với code hiện có, mở khoá tính năng.
- **B (không migration):** gỡ 4 field khỏi form + `only()` — hết 500 nhưng vứt bỏ tính năng social đã code ở 3 view public. Không khuyến nghị (AGENTS.md: ưu tiên tận dụng code cũ).
- **Tuỳ chọn thêm (hỏi user):** thêm luôn cột `job_title` nullable (JSON-LD `jobTitle` đang map tạm từ `description` — `jsonld-person.blade.php:15`). Nếu user đồng ý: cùng migration + thêm input form + `only()` + đổi partial. Nếu không: giữ nguyên map hiện tại (chấp nhận được).

## Related Code Files
- Tạo: `database/migrations/2026_07_23_XXXXXX_add_social_columns_to_about_table.php` (up: 4 cột nullable; down: dropColumn 4 cột).
- Sửa: KHÔNG (controller/form/view đã sẵn sàng). Chỉ sửa thêm nếu chọn tuỳ chọn `job_title`.

## Implementation Steps
1. **Chờ user duyệt phương án A** (+ quyết định `job_title` có/không).
2. Backup local: `mysqldump -u root tuanh about > backup-about.sql` (báo trước khi chạy theo AGENTS.md).
3. Tạo migration, chạy `php artisan migrate` (chỉ chạy migration mới — Laravel tự bỏ qua các migration đã chạy).
4. Verify: `SHOW COLUMNS FROM about` có 4 cột mới; data cũ nguyên vẹn.
5. Ghi chú deploy: production phải chạy cùng migration (backup trước).

## Todo
- [ ] User duyệt schema (phương án A, quyết định job_title)
- [ ] Backup bảng about
- [ ] Tạo + chạy migration
- [ ] Test tay theo Success Criteria
- [ ] Commit: `fix: them cot social cho bang about, sua loi 500 luu profile`

## Success Criteria (test tay)
1. /admin/profile: điền name + 4 URL social → Lưu → không lỗi, reload form thấy giá trị đã lưu.
2. http://127.0.0.1:8000/ : social links hiện ở header (icon) + footer + hero section.
3. Xoá 1 URL social trong admin → lưu → link đó biến mất ở public (điều kiện `@if` hoạt động).
4. `php artisan migrate:rollback --step=1` (chỉ test local) → 4 cột biến mất, data cột cũ nguyên vẹn → migrate lại.

## Risk Assessment
- Thấp: cột nullable mới, không đổi cột cũ, rollback sạch bằng `down()`. Rủi ro duy nhất: quên chạy migration trên prod → bug 500 vẫn còn trên prod (đưa vào checklist deploy).

## Security Considerations
- URL social do admin nhập, in qua `{{ }}` (escaped) tại header/footer/home — OK. Cân nhắc (không bắt buộc): validate `nullable|url|max:255` cho 4 field trong `updateProfile` — thêm 1 dòng, khuyến nghị làm cùng commit.

## Next Steps
- **E-06 phụ thuộc mục này.** Sau khi xong, cập nhật checklist deploy prod (migrate + APP_URL).
