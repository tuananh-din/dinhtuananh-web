# C-04: Tách nốt inline CSS còn lại ra custom.css (tiếp nối B-3)

## Overview
- **Priority:** P1
- **Status:** Pending
- **Migration:** Không
- B-3 mới tách inline CSS của `home.blade.php` (phần lớn) và `contact.blade.php`. Còn 6 chỗ `<style>` trong views public → CSS trùng lặp (`.post-date` khai báo 3 nơi), khó bảo trì. Gom về `custom.css` — thuần frontend, không đổi logic.

## Key Insights (từ đọc code — vị trí đã verify)
Các block `<style>` còn lại:
- `resources/views/home.blade.php:235` — `.post-date` (1 rule, TRÙNG với blogs/blog_detail)
- `resources/views/blogs.blade.php:6` — `.post-date`
- `resources/views/blog_detail.blade.php:19` — `.post-date`, `.blog-share`, `.other-blogs h3`
- `resources/views/courses.blade.php:5` — `.course-list-shell`, `.course-card`, `.course-thumb`, `.course-badge`, `.course-content`, `.course-price` (~65 dòng)
- `resources/views/course_detail.blade.php:23` — block style riêng
- `resources/views/about.blade.php:5` — block style riêng
- `custom.css` đã được nạp toàn cục trong master.blade.php (dòng 52) → mọi class chuyển sang đều có hiệu lực trên mọi trang.
- DRY: `.post-date` 3 bản sao chỉ khác font-size (0.8rem vs 0.85rem) → hợp nhất 1 rule (chọn 0.85rem hoặc 0.8rem, khác biệt không nhận ra bằng mắt).

## Requirements
- Sau khi tách: `grep '<style' resources/views/*.blade.php` không còn kết quả ở views public (admin để nguyên — ngoài scope).
- Giao diện các trang KHÔNG đổi (pixel-level như cũ).

## Related Code Files
- **Sửa:** `public/site/assets/css/custom.css` (thêm sections có comment đánh dấu nguồn), `resources/views/home.blade.php`, `blogs.blade.php`, `blog_detail.blade.php`, `courses.blade.php`, `course_detail.blade.php`, `about.blade.php`
- **Tạo/Xóa:** Không

## Implementation Steps
1. Copy nội dung từng block `<style>` vào cuối `custom.css`, mỗi nhóm có comment `/* C-4: from courses.blade.php */`...
2. Hợp nhất `.post-date` thành 1 rule duy nhất (xóa 2 bản trùng).
3. Xóa các thẻ `<style>...</style>` khỏi 6 view.
4. Kiểm tra không có selector đụng độ với rule đã tách ở B-3 (search class name trong custom.css trước khi thêm).

## Todo
- [ ] Tách style của home (dòng 235)
- [ ] Tách style của blogs
- [ ] Tách style của blog_detail
- [ ] Tách style của courses
- [ ] Tách style của course_detail
- [ ] Tách style của about
- [ ] Hợp nhất .post-date
- [ ] Test tay theo Success Criteria

## Success Criteria (test tay)
1. Mở lần lượt `/`, `/blog`, 1 bài blog, `/courses`, 1 course detail, `/about` — so sánh trước/sau (screenshot), layout giống hệt.
2. View-source không còn thẻ `<style>` ở các trang trên.
3. Ctrl+F5 (hard refresh) để chắc chắn không phải cache CSS cũ.

## Risk Assessment
- **Thấp-trung bình.** Rủi ro: specificity thay đổi (style trong `<section>` load sau main.css vs custom.css load trong head). custom.css đã nạp SAU main.css nên thứ tự override giữ nguyên; nếu 1 rule bị main.css đè, thêm specificity bằng selector gốc (không dùng !important).
- Rủi ro cache browser giữ custom.css cũ → hướng dẫn user hard refresh.
- Rollback: revert commit (1 commit riêng cho C-04).

## Security Considerations
- Không có — thuần CSS, không nhận input người dùng.
