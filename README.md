# tanh

Website personal brand bằng Laravel: phần public giới thiệu nội dung, khóa học và blog; phần admin quản lý nội dung.

## Bắt đầu local

1. Cài PHP 8.1+ và Composer; dự án hiện chạy local qua Laragon.
2. Tạo `.env` từ `.env.example`, sau đó tự điền cấu hình database và mail của môi trường local. Không commit `.env` hoặc credential.
3. Cài dependency: `composer install`.
4. Chỉ trên database phát triển trống, chạy migration/seed theo hướng dẫn chính thức trong tài liệu dự án.
5. Tạo storage link khi cần hiển thị ảnh upload: `php artisan storage:link`.

PHP Laragon có thể chưa nằm trong `PATH`. Xem lệnh PHP đã xác minh và cách chạy test trong [PROJECT-INSTRUCTIONS.md](PROJECT-INSTRUCTIONS.md#9-test-hiện-trạng-và-cách-chạy).

## Tài liệu

- [PROJECT-INSTRUCTIONS.md](PROJECT-INSTRUCTIONS.md): kiến trúc, quy ước, môi trường local và checklist phát triển.
- [PROJECT-GAP-AUDIT.md](PROJECT-GAP-AUDIT.md): audit, rủi ro còn lại và roadmap.
- [Changelog dự án](docs/project-changelog.md): lịch sử các phase và commit.
- [Hướng dẫn triển khai](docs/deployment-guide.md): checklist cấu hình/triển khai; không coi đây là lệnh để deploy tự động.

## Lưu ý

- Test dùng SQLite in-memory, tách khỏi database local. Chạy toàn bộ suite theo lệnh trong tài liệu hướng dẫn.
- Không đổi schema, route hoặc hạ tầng ngoài phase được duyệt.
- Ảnh admin nằm trong vùng storage public; cần kiểm tra storage link ở môi trường chạy thật.
