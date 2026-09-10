# Xuất bản Digital Performance Management trên Tino

1. Trong cPanel → Git Version Control, chọn repository website.
2. Chọn **Update from Remote**, sau đó **Deploy HEAD Commit** và đợi hoàn tất.
3. Mở **Terminal** trong cPanel, chạy:

```sh
cd /home/dinhtuan1/public_html
/usr/local/bin/php artisan db:seed --class=PublishDigitalPerformanceCourseSeeder --force
/usr/local/bin/php artisan view:clear
```

Lệnh xuất bản tạo khóa nếu chưa có, bật hiển thị và thêm ảnh đại diện nếu còn trống. Nội dung và giá đã sửa trong quản trị được giữ nguyên. Không cần migration mới. Không chạy toàn bộ DatabaseSeeder.

4. Kiểm tra trong cửa sổ ẩn danh:
   - https://dinhtuananh.com/courses — có thẻ khóa học, học phí và ảnh.
   - Bấm **Xem chi tiết** → https://dinhtuananh.com/courses/digital-performance-management.
   - Kiểm tra form đăng ký có thể chọn đúng khóa học.

Nếu cần tạm ngừng nhận đăng ký, vào quản trị → Khóa học → sửa khóa → tắt Hiển thị. Không chạy lại lệnh xuất bản sau khi chủ động ẩn khóa. Các lần deploy giao diện tiếp theo không cần chạy lệnh này.
