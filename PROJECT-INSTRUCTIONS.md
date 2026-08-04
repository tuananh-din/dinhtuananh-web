# PROJECT INSTRUCTIONS — tanh

Tài liệu kỹ thuật để phát triển và vận hành tiếp dự án. Khi tài liệu mâu thuẫn với mã nguồn hiện tại, ưu tiên mã nguồn rồi cập nhật lại tài liệu. Đọc `AGENTS.md` trước mọi thay đổi.

## 1. Tổng quan production

- Website personal brand tiếng Việt, bán khóa học và thu lead tư vấn; domain: `https://dinhtuananh.com`.
- Stack: Laravel 10, PHP 8.1+ (production đang dùng PHP 8.3), Blade, Eloquent và session auth.
- Database: MySQL trên production; PHPUnit dùng SQLite in-memory.
- Hosting: Tino cPanel. Tài khoản home là `/home/dinhtuan1`, ứng dụng đặt tại `/home/dinhtuan1/public_html`.
- GitHub: `tuananh-din/dinhtuananh-web`, nhánh triển khai: `master`.
- Local: Laragon tại `C:\App\laragon\www\tanh`, dùng MySQL Laragon khi cần kiểm tra hành vi MySQL.

## 2. Kiến trúc ứng dụng

### Routes public

| Nhóm | Route chính |
| --- | --- |
| Trang nội dung | `GET /`, `/about`, `/life`, `/portfolio`, `/contact` qua `HomeController` |
| Chuyển đổi | `GET /cam-on`, `POST /lead/store`, `POST /newsletter/subscribe`, `POST /lead-magnet/{id}/subscribe` |
| Blog | `GET /blog` (tìm kiếm/lọc chuyên mục), `GET /{slug}.html` |
| Khóa học | `GET /courses`, `GET /courses/{slug}` |
| SEO/vận hành | `GET /sitemap.xml`, `GET /health` |
| Xác thực | `GET /login`, `POST /login`, `POST /logout` |

`/{slug}.html` là catch-all cho blog, luôn phải đặt sau các route cụ thể. Các POST lead/newsletter/lead magnet có `throttle:5,1`; form public dùng CSRF và honeypot.

`/health` không cần đăng nhập: thử `DB::select('select 1')`, trả `{"status":"ok"}` HTTP 200 hoặc `{"status":"error"}` HTTP 503; không trả chi tiết lỗi.

### Nhóm Admin

Toàn bộ `/admin/**` nằm trong middleware `auth`:

- Dashboard: `/admin` qua `DashboardController`.
- Cài đặt, profile và upload CKEditor: `SettingController`, `AboutController`.
- CRUD: Blog, Category, Course, Lead, LeadMagnet, Testimonial, Service, Skill và Image trong `App\Http\Controllers\Admin\*`.
- Các controller public là `HomeController`, `BlogController`, `CourseController`, `LeadController`, `LeadMagnetController`, `NewsletterController`, `LoginController`, `SitemapController`.

Giữ các route legacy và route name hiện có; dùng `route()` thay vì tự ghép URL. Xóa Admin là POST có CSRF và confirm.

### Model và quan hệ

- `Blog` thuộc bảng `blogs`, `belongsToMany(Category::class, 'blog_category')`.
- `Category` có `belongsToMany(Blog::class, 'blog_category')`.
- `Lead` dùng `SoftDeletes`, có `Lead::STATUSES` (`new`, `contacted`, `qualified`, `won`, `lost`) và `belongsTo(Course::class)`.
- `Subscriber` thuộc bảng `subscribers`; `LeadMagnet` thuộc bảng `lead_magnets`.
- Các model CMS còn lại: `About`, `Course`, `Image`, `Service`, `Setting`, `Skill`, `Testimonial`, `User`.

### Database

Các bảng nghiệp vụ chính: `users`, `setting`, `about`, `services`, `skills`, `images`, `blogs`, `courses`, `leads`, `testimonials`, `socials` (legacy, không dùng), `subscribers`, `categories`, `blog_category`, `lead_magnets`.

- `subscribers`: email unique, source nullable, timestamps.
- `categories`: `id` là `INT unsigned` từ `increments()`, name, slug unique, timestamps.
- `blog_category`: `blog_id` và `category_id` là `unsignedInteger`; unique cặp; FK cascade tới `blogs` và `categories`.
- `lead_magnets`: tên, mô tả, đường dẫn PDF, ảnh bìa, cờ active, timestamps.
- `leads` có `deleted_at`; danh sách bình thường tự loại lead đã xóa mềm. `leads.course_id` vẫn không có FK database.

## 3. Tính năng đã có

### NHÓM 1 — Thu lead và chuyển đổi

- Newsletter lưu `subscribers`; sau khi lưu thành công, đồng bộ Brevo không chặn request. Email trùng được xử lý lịch sự.
- Form tư vấn và newsletter chuyển về `/cam-on`; layout có `@stack('conversion')` để đặt Pixel/GA.
- Lead magnet: Admin quản lý tài liệu PDF/ảnh bìa; khách để email nhận mail tải tài liệu. Subscriber mới từ lead magnet cũng đồng bộ Brevo non-blocking.
- Dashboard Admin có phân tích lead theo `source_page`, 7/30 ngày và tỷ lệ trạng thái.

### NHÓM 2 — SEO và traffic

- JSON-LD Course cho trang chi tiết khóa học.
- Chuyên mục blog: CRUD Admin, chọn nhiều chuyên mục khi sửa blog, tìm kiếm title/description và lọc public theo chuyên mục.
- JSON-LD BreadcrumbList cho blog và khóa học; public SEO đã có canonical/OG và ảnh fallback theo logic view hiện có.
- Ảnh nội dung công khai dùng `loading="lazy"`.

### NHÓM 3 — Accessibility và Admin

- Public có skip-to-content, `:focus-visible`, ARIA cho menu/toggle, label/aria-label form và alt text theo ngữ cảnh.
- Admin dùng branding của site ở sidebar, header và login; chỉ chỉnh CSS/asset, không đổi logic.

### NHÓM 4 — Vận hành

- `/health` dùng cho monitoring uptime và cả kiểm tra kết nối DB.
- `php artisan backup:db` dump MySQL, nén gzip vào `storage/app/backups`, giữ 14 ngày.

## 4. Deploy

Quy trình chuẩn:

1. Codex hoàn tất thay đổi nhỏ, test và commit.
2. Claude review diff.
3. Chạy `git push origin master`.
4. Trong cPanel: **Git Version Control** → repo → **Update from Remote** → **Deploy HEAD Commit**.

`.cpanel.yml` copy vào `public_html` các phần: `app`, `bootstrap`, `config`, `database`, `resources`, `routes`, `public/app`, `public/site`, `public/index.php`, `public/.htaccess`, `artisan`, `composer.json`, `composer.lock`. Sau đó nó chạy:

```bash
/usr/local/bin/php artisan migrate --force
/usr/local/bin/php artisan config:clear
/usr/local/bin/php artisan view:clear
```

Kịch bản cố ý **không** copy đè `.env`, `vendor`, `storage`, `public/storage` hay `.htaccess` gốc ở docroot để giữ cấu hình, dependency, upload và email server. Không thêm chúng vào phạm vi deploy nếu chưa được duyệt.

## 5. Đặc thù server Tino/cPanel

- Home: `/home/dinhtuan1`; application: `/home/dinhtuan1/public_html`.
- Docroot dùng mẹo `.htaccess` redirect vào thư mục `/public/`; phải giữ nguyên cơ chế này.
- PHP CLI: `/usr/local/bin/php`.
- `composer` không có trên PATH server; có thể dùng `composer.phar` khi thật sự cần và đã được duyệt.
- `mysqldump`: `/bin/mysqldump`.
- Giữ quyền ghi cho `storage/framework/*` (thông thường chmod `775`).
- Giữ các view phân trang đã deploy: `resources/views/vendor/paginate.blade.php` và `resources/views/vendor/pagination.blade.php`.
- `vendor`, `storage` và `public/storage` không được `.cpanel.yml` copy; cần tồn tại sẵn trên server.

## 6. Vận hành và backup

- UptimeRobot theo dõi `https://dinhtuananh.com/health`.
- JetBackup 5 của Tino là lớp backup full account (file và DB).
- Lớp backup DB riêng: `php artisan backup:db` tạo `storage/app/backups/db-YYYY-MM-DD-HHmmss.sql.gz`, tự xóa file `.sql.gz` cũ hơn 14 ngày.
- Cron cPanel đã chọn lịch hằng đêm `0 0 * * *`; command:

```bash
cd /home/dinhtuan1/public_html && /usr/local/bin/php artisan backup:db >> /home/dinhtuan1/backup-db.log 2>&1
```

Khi kiểm tra sự cố, xem `/home/dinhtuan1/backup-db.log`, dung lượng file trong `storage/app/backups`, và restore point JetBackup trước khi làm thao tác khôi phục.

## 7. Cấu hình `.env` và bảo mật secret

- Gmail SMTP: các biến `MAIL_*`; người nhận lead: `LEAD_NOTIFY_EMAIL`.
- Brevo: `BREVO_API_KEY`, `BREVO_LIST_ID`. Chưa bật đồng bộ production cho đến khi xác minh SĐT Brevo; sau đó đặt key/list ID, chạy `php artisan config:clear` và `php artisan newsletter:test-brevo <email>`.
- Backup: `BACKUP_MYSQLDUMP_PATH=/bin/mysqldump` (có thể override khi server khác đường dẫn).
- Không commit `.env`, không đưa password, API key, App Password, token hoặc dữ liệu khách hàng vào Git, diff hay chat.

Email lead được lưu trước; lỗi gửi email chỉ log, không chặn lưu lead. SMTP local có thể bị chặn mạng, nên kiểm tra gửi thật nên thực hiện trên server sau deploy.

## 8. Quy trình phát triển

- Đọc `AGENTS.md`, file này, changelog, `git status` và route/controller/model/view liên quan trước khi sửa.
- Codex làm code; Claude lập kế hoạch và review diff. Không tự mở rộng scope, refactor lớn hay đổi route/schema legacy.
- Ưu tiên thay đổi nhỏ, tận dụng code cũ và giữ style giao diện hiện có.
- Trước khi code phải báo: thay đổi dự kiến, file ảnh hưởng, có migration hay không, và cách test. Nếu chưa chắc, hỏi lại.
- Mỗi mục một commit. Chạy `php artisan test` trước commit; báo rõ file, route, migration, test tay và rủi ro sau khi xong.
- Migration phải idempotent: dùng `Schema::hasTable()` hoặc `Schema::hasColumn()` phù hợp và có `down()`; chỉ thêm cột nullable khi đó là yêu cầu.
- Local chạy bằng Laragon. PHP đã xác minh:

```powershell
$php = 'C:\App\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe'
& $php artisan test
```

## 9. Bẫy đã gặp

1. Production DB có drift: migration phải idempotent, không giả định schema local giống server.
2. Pivot `blog_category` phải dùng `unsignedInteger`, không dùng bigint, vì `blogs`/`categories` dùng `increments()` (`INT unsigned`).
3. Deploy không copy `vendor` và `storage`; phải chuẩn bị dependency, storage và quyền ghi sẵn trên server.
4. SMTP có thể bị mạng local chặn; kiểm tra kết nối/gửi thật ở server khi cần.
5. Slug blog phải unique; khi tạo slug cần dedup, nhưng khi edit giữ slug hiện có theo pattern cũ.
6. Lead dùng soft delete; không chuyển sang xóa cứng và chưa có trang thùng rác.
7. Nội dung CKEditor ở `public/media` có thể có ảnh orphan; không tự xóa khi chưa có chiến lược tham chiếu an toàn.

## 10. Việc còn lại và hướng phát triển

Các mục này chưa được tự triển khai nếu chưa có yêu cầu riêng:

- Bật Brevo: xác minh SĐT, đặt key/list ID trong `.env`, `config:clear`, test `newsletter:test-brevo`.
- NHÓM 3.1: đo và tối ưu Lighthouse/PageSpeed.
- NHÓM 4.3: dọn ảnh orphan CKEditor bằng chiến lược an toàn.
- NHÓM 4.4: roles/permissions nếu có nhiều admin.
- Bổ sung nội dung thật: blog, khóa học và ít nhất một PDF lead magnet.
- Xóa hoặc thay bài blog test còn lại trước khi quảng bá site.

Xem lịch sử thay đổi đã commit trong `docs/project-changelog.md` và hướng dẫn triển khai chi tiết trong `docs/deployment-guide.md`.
