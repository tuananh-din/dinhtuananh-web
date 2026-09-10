# Trang khóa học Digital Performance Management

Trang giới thiệu và đăng ký tư vấn khóa Digital Performance Management do Đinh Tuấn Anh giảng dạy, học phí khởi tạo 3.000.000 VNĐ, học qua video và nhóm hỏi đáp, chữa bài. Nội dung được viết lại theo nhóm chủ đề tham khảo từ trang PMAX; không sử dụng ảnh, logo, thành tích, đánh giá học viên hoặc lịch khai giảng của PMAX.

## Tích hợp

- Giữ route `GET /courses/{slug}` và route preview admin hiện có. Slug `digital-performance-management` chọn giao diện riêng; khóa khác tiếp tục dùng giao diện cũ.
- Không thêm migration, không đổi schema, không thêm tài khoản học viên, phát video hoặc thanh toán.
- Form POST vào `lead.store`, có CSRF, honeypot, course_id và source_page. Tiếp tục dùng kiểm tra dữ liệu, giới hạn gửi, thông báo email và trang cảm ơn hiện tại.
- Hồ sơ lấy từ bản ghi About hiện có: name, avatar, description, about_me. Repo không chứa database production nên chưa thể xác minh nội dung hồ sơ thật; preview độc lập chỉ hiển thị tên và monogram.
- Giá và mô tả ngắn đọc từ Course. Nội dung bổ sung trong trường content hiển thị dưới lộ trình. Chín chủ đề nằm trong `config/digital-performance.php`.
- CSS chỉ áp dụng trong `.dpm-page`. Tắt ScrollSmoother cho `body.is-dpm` để dùng cuộn native và điều hướng tới các mục.

## File sửa

- app/Http/Controllers/CourseController.php
- app/Http/Controllers/Admin/CourseController.php
- public/site/assets/js/main.js

## File tạo

- config/digital-performance.php
- database/seeders/DigitalPerformanceCourseSeeder.php
- resources/views/courses/digital-performance.blade.php
- public/site/assets/css/digital-performance.css
- tests/Feature/DigitalPerformanceLandingTest.php
- docs/digital-performance-landing.md

## Đưa lên môi trường chạy — chưa thực hiện

Sau khi review và được phép triển khai, áp dụng các file vào repo, kiểm tra diff và chạy tests. Tiếp tục quy trình GitHub → cPanel của dự án. Cấu hình deploy hiện tại đã copy các thư mục cần thiết.

Trong application root của môi trường được phép, chạy đúng seeder này một lần:

```sh
php artisan db:seed --class=DigitalPerformanceCourseSeeder --force
php artisan config:clear
php artisan view:clear
```

Không chạy lại toàn bộ DatabaseSeeder hoặc migrate:fresh trên database thật. Seeder mới chỉ tạo khóa nếu slug chưa có; chạy lại không ghi đè chỉnh sửa hiện có. Khóa được tạo ở trạng thái ẩn.

Vào Admin → Khóa học → Digital Performance Management → Xem trước. Kiểm tra hồ sơ giảng viên và nội dung, sau đó bật trạng thái hoạt động khi đã sẵn sàng. URL công khai sử dụng route hiện có: `/courses/digital-performance-management`. Giữ slug này để tiếp tục sử dụng giao diện riêng.

## Kiểm tra đã thực hiện

- `php artisan test --compact`: 78 tests passed, 371 assertions trên PHP 8.3.33 và SQLite in-memory.
- Kiểm tra khóa nháp bị 404 ở public, khách chưa đăng nhập không vào được preview, admin xem đúng giao diện.
- Kiểm tra hồ sơ database và giá đã thay đổi hiển thị đúng, gửi lead gắn đúng khóa, seeder không ghi đè dữ liệu.
- Các tests khóa cũ, đăng ký, admin, SEO và sitemap hiện có đều đạt.
- `git diff --check` không báo lỗi.

## Kiểm tra tay khi tích hợp

1. Xem trang ở màn hình lớn và điện thoại; thử menu mục và mở/đóng chương.
2. Kiểm tra ảnh, kinh nghiệm thật lấy từ hồ sơ admin; nếu chưa có ảnh, monogram sẽ xuất hiện.
3. Kiểm tra giá 3.000.000 VNĐ và các quyền lợi; chỉnh thông tin còn thiếu trước khi công khai.
4. Gửi form thử bằng thông tin kiểm thử được cho phép. Kiểm tra lead và email trên môi trường đã cấu hình SMTP.
5. Kiểm tra một khóa khác vẫn dùng giao diện cũ.

## Giới hạn còn lại

Chưa push GitHub, chưa deploy Tino, chưa kết nối database thật hoặc kiểm tra SMTP production. Chưa kiểm tra giao diện bằng trình duyệt tự động. Thời hạn xem video chưa được xác nhận nên trang hướng người học trao đổi khi tư vấn. Không tự đưa số năm kinh nghiệm, khách hàng, thành tích hoặc nhận xét học viên chưa có căn cứ lên trang.

Bản HTML xem trước là bản độc lập của phần nội dung trang, chưa bao gồm header/footer Laravel hiện tại và không gửi dữ liệu form.

## Cập nhật theo ảnh tham khảo — 11/09/2026

- Đối tượng: sinh viên định hướng Marketing, marketer muốn phát triển, người mới/kinh doanh/chuyển ngành.
- Đổi sang 9 buổi theo đúng thứ tự ảnh: Overview/Mindset; Approach/Media Plan; Creative/Content Plan; Owned/Earned; Facebook/TikTok; Google GDN/YouTube/SEM/Shopping; thực hành ba nền tảng; Optimization; Tracking/Analytics.
- Thêm tài liệu đi kèm trong quyền lợi. Giữ học phí 3.000.000 VNĐ và nhóm hỏi đáp, chữa bài.
- Bỏ bảng màu xanh rêu của bản đầu. Kế thừa --theme, --header, --bg trong CSS gốc. Dark: #BFF747 / #FFF / #1A1A1A; light: #5f8f16 / #171a1f / #eef1f5. Nền trang dark #060606, light #f6f7f9 theo CSS hiện có.
- Bản xem trước lấy nguyên khai báo biến CSS từ Git commit dd5c215e67beb4526bd6f35cb243a15113db8801, có nút Sáng/Tối. Khi tích hợp dùng nút đổi theme hiện có của web.
- Chạy lại: 78 tests passed, 371 assertions. Kiểm tra giá trị màu trong mã nguồn, chưa xác nhận bằng ảnh chụp trình duyệt.
- Không thêm route hoặc migration. Chưa push/deploy.

## Bổ sung hình ảnh và biểu đồ

- Ảnh minh họa AI mới tại public/site/assets/img/courses/digital-performance-workspace.png; không đại diện ảnh lớp học, giảng viên hoặc khách hàng thật.
- Thêm partial resources/views/partials/dpm-analytics.blade.php với ba biểu đồ SVG: doanh thu/chi phí, CPL và hành trình chuyển đổi.
- Dữ liệu giả định được ghi rõ trên trang; có bảng số liệu và công thức, không lấy số liệu trong ảnh người dùng để gán thành tích cho giảng viên.
- SVG kế thừa màu từ CSS website; có nhãn truy cập, responsive và số liệu dạng bảng. Không tải thư viện chart bên ngoài.
- Ảnh đầu trang có kích thước khai báo và ưu tiên tải. File ảnh đã nằm trong public/site nên được quy trình cPanel hiện có copy cùng trang.
- Không thêm route, migration hoặc thay đổi xử lý đăng ký.
