# C-05: Chuẩn hóa ảnh blog — asset() + fallback (hoàn thiện A-1)

## Overview
- **Priority:** P1
- **Status:** Pending
- **Migration:** Không
- A-1 đã chuẩn hóa asset() cho layout/logo/favicon nhưng ảnh blog trong card vẫn dùng đường dẫn TƯƠNG ĐỐI: `{{ $row->image ?: 'app/assets/images/others/thumb-16.jpg' }}`. Hiện chạy được vì các route đều 1 cấp (`/blog`, `/{slug}.html`), nhưng là bom nổ chậm: thêm route 2 cấp hoặc chạy trong subfolder là vỡ ảnh. Chuẩn hóa theo đúng pattern đã dùng ở master/header (bọc asset() khi path tương đối).

## Key Insights (từ đọc code — vị trí đã verify)
- `resources/views/home.blade.php:247` — `<img src="{{ $row->image ?: 'app/assets/...' }}">`
- `resources/views/blogs.blade.php:17` — cùng pattern
- `resources/views/blog_detail.blade.php:54` (otherBlogs) — cùng pattern
- Pattern chuẩn đã tồn tại trong project (master.blade.php dòng 8-10, header.blade.php dòng 7-9): `Str::startsWith($path, ['http://','https://','//','/']) ? $path : asset($path)`.
- Giá trị `image` trong DB có thể là URL tuyệt đối (upload CKEditor) hoặc path tương đối → phải giữ logic phân nhánh, KHÔNG bọc asset() mù quáng.
- DRY: 3 chỗ cùng logic → nên tạo 1 helper Blade component/hàm. KISS nhất: 1 hàm helper nhỏ hoặc `@php` cục bộ. Đề xuất: thêm accessor hoặc helper — xem Steps.

## Requirements
- Ảnh blog hiển thị đúng ở mọi route depth; fallback thumb khi `image` rỗng.
- Không đổi dữ liệu DB, không đổi cách admin lưu ảnh.

## Related Code Files
- **Sửa:** `resources/views/home.blade.php`, `resources/views/blogs.blade.php`, `resources/views/blog_detail.blade.php`
- **Tạo:** `app/Helpers/image-url-helper.php` HOẶC method trong model — xem lựa chọn bên dưới
- **Xóa:** Không

## Implementation Steps
1. **Chọn phương án (đề xuất A):**
   - **A (KISS, đề xuất):** thêm accessor `getImageUrlAttribute()` vào `app/Models/Blog.php`: trả fallback thumb nếu rỗng, giữ nguyên nếu absolute, bọc `asset()` nếu tương đối. Không cần autoload file mới.
   - B: helper function global — nặng hơn (sửa composer.json), bỏ qua (YAGNI).
2. Thay 3 chỗ `{{ $row->image ?: '...' }}` thành `{{ $row->image_url }}` (home, blogs) và `{{ $other->image_url }}` (blog_detail).
3. Kiểm tra `og_image` trong blog_detail.blade.php dòng 15 — đã xử lý đúng từ A-4, không đụng.

## Todo
- [ ] Thêm accessor image_url vào Blog model
- [ ] Sửa home.blade.php:247
- [ ] Sửa blogs.blade.php:17
- [ ] Sửa blog_detail.blade.php:54
- [ ] Test tay theo Success Criteria

## Success Criteria (test tay)
1. `/` (section blog), `/blog`, và 1 bài blog (mục "Bài viết khác") — mọi ảnh hiển thị đúng (cả bài có ảnh và không ảnh với 2 blog seed).
2. Sửa tạm 1 blog trong DB: image = URL tuyệt đối `https://...` → hiển thị nguyên URL; image = rỗng → hiện thumb fallback; xong revert DB.
3. View-source: mọi `src` ảnh blog là URL tuyệt đối bắt đầu bằng `http`.

## Risk Assessment
- **Thấp.** Accessor chỉ đọc, không ghi. Nếu format DB bất ngờ (VD path có khoảng trắng) → asset() vẫn trả URL, tệ nhất là ảnh 404 hiện icon vỡ — bằng hiện trạng.
- Lưu ý thứ tự với C-04: cùng đụng 3 file view này → làm SAU khi C-04 đã commit.
- Rollback: revert commit riêng của C-05.

## Security Considerations
- `image` do admin nhập — vẫn escape qua `{{ }}` (không dùng `{!! !!}`), không có XSS mới.
