# C-03: Trang 404 tùy brand

## Overview
- **Priority:** P1
- **Status:** Pending
- **Migration:** Không
- Hiện chưa có `resources/views/errors/` → gõ sai URL blog (`/abc.html` không tồn tại, `firstOrFail` ném 404) sẽ thấy trang 404 trắng mặc định của Laravel — mất trải nghiệm brand, mất điều hướng. Tạo trang 404 dùng layout site, có CTA về trang chủ/blog.

## Key Insights (từ đọc code)
- `BlogController@detail` và `CourseController@detail` dùng `firstOrFail()` → 404 là tình huống chắc chắn gặp thực tế (link cũ, gõ sai slug).
- Laravel tự động dùng `resources/views/errors/404.blade.php` nếu tồn tại — không cần sửa Handler, không cần route.
- View composer `*` (AppServiceProvider) inject `$infor`/`$contact` cho MỌI view kể cả trang lỗi → layout master hoạt động bình thường trong trang 404.
- Lưu ý: master.blade.php nạp rất nhiều JS (gsap, swiper...) — chấp nhận vì tái dùng layout là cách nhỏ, an toàn nhất (KISS), không tạo layout riêng.

## Requirements
- Trang 404 kế thừa `layouts.master`, tiêu đề "Không tìm thấy trang", thông điệp lịch sự tiếng Việt.
- CTA: về Trang chủ + xem Blog + xem Khóa học.
- Trả đúng HTTP status 404 (Laravel tự lo khi dùng file errors/404).

## Related Code Files
- **Tạo:** `resources/views/errors/404.blade.php`
- **Sửa:** `public/site/assets/css/custom.css` (thêm vài dòng style `.error-page` nếu cần — KHÔNG inline style, theo tinh thần B-3)
- **Xóa:** Không

## Implementation Steps
1. Tạo `resources/views/errors/404.blade.php`:
   - `@extends('layouts.master')`, `@section('page_title', 'Không tìm thấy trang | ' . data_get($infor,'name','Personal Brand'))`.
   - Section content: heading 404, mô tả ngắn, 3 nút theo class `theme-btn` có sẵn (`route('index')`, `route('blogs')`, `route('courses')`).
2. Thêm block `.error-page` vào cuối `custom.css` (padding, căn giữa) — tối giản.
3. (Tùy chọn, cùng pattern) tạo `errors/500.blade.php` tương tự — CHỈ làm nếu user đồng ý, mặc định chỉ 404 (YAGNI).

## Todo
- [ ] Tạo errors/404.blade.php theo layout master
- [ ] Thêm style .error-page vào custom.css
- [ ] Test tay theo Success Criteria

## Success Criteria (test tay)
1. Mở `/bai-khong-ton-tai.html` → thấy trang 404 theo brand (header/footer đầy đủ), DevTools Network tab hiển thị status 404.
2. Mở `/courses/khong-ton-tai` → cùng trang 404.
3. Các nút CTA điều hướng đúng.
4. Trang bình thường không bị ảnh hưởng.

## Risk Assessment
- **Thấp.** File view mới, không đụng code cũ. Nếu layout lỗi trong context error (thiếu biến), fallback: kiểm tra `$infor` null-safe — layout đã dùng `data_get` nên an toàn.
- Rollback: xóa file 404.blade.php → về 404 mặc định.

## Security Considerations
- Không echo URL/slug người dùng nhập vào trang 404 (tránh reflected XSS qua thông điệp lỗi).
