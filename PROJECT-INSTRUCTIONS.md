# PROJECT INSTRUCTIONS — tanh

> Tài liệu bối cảnh kỹ thuật để phát triển tiếp dự án mà không cần đọc lại toàn bộ mã nguồn. Cập nhật theo source tại ngày 24/07/2026. Khi tài liệu và mã nguồn mâu thuẫn, **ưu tiên mã nguồn hiện tại** và cập nhật lại tài liệu sau khi xác minh.

## 1. Tổng quan dự án

Đây là website personal brand bằng tiếng Việt, định vị bán khóa học marketing/ads. Website gồm hai phần:

- **Public site:** giới thiệu cá nhân, dịch vụ/kỹ năng/case study, blog, danh sách và chi tiết khóa học, form nhận tư vấn (lead), sitemap và trang 404.
- **Admin CMS:** đăng nhập một tài khoản `users`, quản lý thông tin cá nhân, setting site, blog, khóa học, lead, testimonial, dịch vụ, kỹ năng và ảnh.

### Stack đã xác minh

| Thành phần | Công nghệ / cách dùng |
| --- | --- |
| Backend | PHP `^8.1`, Laravel `^10.10`, Eloquent ORM, Blade, session auth |
| Database | MySQL là mặc định; schema qua Laravel migrations |
| Xác thực | Guard `web`, provider Eloquent `App\Models\User`, session; Sanctum đã cài nhưng API thực tế chỉ có `/api/user` mặc định |
| Upload ảnh | `intervention/image-laravel`; ảnh quản trị lưu tại disk `public` dưới `storage/app/public/images`, URL `/storage/...` |
| Email | Laravel Mailable `NewLeadNotification`; gửi đồng bộ sau khi tạo lead, lỗi gửi chỉ ghi log |
| Public UI | Blade + theme tĩnh trong `public/site/assets`; Bootstrap, jQuery 3.7.1, GSAP, Swiper, MeanMenu, Toastr; CSS bổ sung `custom.css` |
| Admin UI | Theme tĩnh trong `public/app/assets`; CKEditor 5 tải từ CDN cho nội dung blog/setting/profile |
| Build frontend | Vite 5 có cấu hình cho `resources/css/app.css` và `resources/js/app.js`, nhưng các màn hình hiện tại chủ yếu nạp asset tĩnh trực tiếp, không thấy `@vite` trong view |
| Test/code style | PHPUnit 10; Laravel Pint đã có trong `require-dev` |

Kiến trúc là Laravel MVC mỏng: route gọi controller, controller query trực tiếp model và trả Blade view. **Không có `app/Services`, Form Request, Repository, Job hay Event nghiệp vụ riêng trong source hiện tại.** Hãy giữ thay đổi nhỏ theo phong cách hiện có, trừ khi đã được chấp thuận để cải tổ.

## 2. Quy ước làm việc bắt buộc

### Quy ước encoding tiếng Việt

- Lưu Blade, PHP, Markdown và cấu hình text bằng **UTF-8**; không copy chuỗi đã bị lỗi mã hóa kiểu `HÃ£y` hoặc `phÃ¹ há»£p`.
- Với nội dung HTML cũ đang dùng numeric HTML entities, có thể tiếp tục dùng entities cho ký tự tiếng Việt khi sửa đúng vùng đó để thay đổi nhỏ và tránh lỗi mã hóa từ editor/terminal.
- Trước khi commit nội dung public tiếng Việt, kiểm tra source và xem tay trang liên quan; dữ liệu do admin nhập trong database cần xác minh riêng vì không thể kết luận chỉ từ source.
- Không đặt numeric HTML entity làm fallback bên trong `{{ ... }}`: Blade sẽ escape dấu `&`. Dùng chuỗi UTF-8 trực tiếp cho fallback text động, hoặc render entity ngoài biểu thức Blade khi thật sự cần.

### Quy ước card public và responsive

- Nội dung do admin nhập trong card phải được phép xuống dòng; với card dùng Flexbox, giữ `min-width: 0` và `overflow-wrap: anywhere` tại vùng text động để title/mô tả dài không tràn khỏi card hoặc badge.
- Không dùng chiều cao cố định để ép các card đều nhau. Dùng layout cột + `flex: 1` cho phần mô tả khi cần căn CTA, và chỉ điều chỉnh padding theo breakpoint nhỏ.
- Khi sửa card public, kiểm tra tối thiểu desktop và viewport 360px/768px bằng trình duyệt; nếu công cụ xem trực quan không khả dụng, ghi rõ giới hạn này và không khẳng định kết quả bằng mắt.

### Quy ước header mobile

- Nút mở/đóng offcanvas phải là `<button type="button">`, có nhãn tiếng Việt và trạng thái `aria-expanded`/`aria-hidden`; giữ class hook JavaScript cũ khi thay đổi markup.
- Không giảm vùng chạm menu mobile dưới kích thước theme hiện có (60px). CTA chữ dài được ẩn dưới breakpoint menu ngang để tránh làm header chật; menu offcanvas là kênh điều hướng thay thế.

### Quy ước CTA khóa học

- `cta_link` là dữ liệu admin nhưng được render trực tiếp thành `href`, nên phải validate ở server. Chỉ chấp nhận URL `http/https`, đường dẫn nội bộ bắt đầu bằng một dấu `/` (không phải `//...`), hoặc `tel:` có số điện thoại hợp lệ.
- Không tự đổi dữ liệu CTA đã lưu trong migration. Trước khi siết rule mới, rà các giá trị hiện có để tránh chặn format hợp lệ đang dùng; hiện dữ liệu production/local mẫu sử dụng `tel:`.

Đọc `AGENTS.md` ở root trước mọi phase. Các yêu cầu quan trọng:

- Trao đổi và tài liệu bằng tiếng Việt, dễ hiểu.
- Không tự suy đoán điểm chưa chắc; ghi `cần xác minh` hoặc hỏi lại trước khi code.
- Không tự mở rộng phase, refactor lớn, đổi schema hay cấu trúc route cũ.
- Trước phase phải báo: sửa gì, file bị ảnh hưởng, có migration không, cách test.
- Sau phase phải báo: file sửa/tạo, route/migration, cách test tay, rủi ro còn lại.
- Không deploy production, không xóa hàng loạt code/module khi chưa được yêu cầu.
- Giữ style giao diện hiện có; có fallback khi dữ liệu rỗng.

Repo Git đang có lịch sử phase A–E trong `docs/project-changelog.md`. Các file `plans/**/plan.md` vẫn có nhiều nhãn `Pending`, nhưng changelog và mã nguồn cho thấy phase C, D, E đã được thực hiện; xem chúng là **kế hoạch lịch sử, không phải trạng thái hiện hành**.

## 3. Cấu trúc thư mục quan trọng

| Đường dẫn | Vai trò |
| --- | --- |
| `app/Http/Controllers/` | Controller public: Home, Blog, Course, Lead, Login, Sitemap. |
| `app/Http/Controllers/Admin/` | CRUD/setting cho khu quản trị. |
| `app/Models/` | Model Eloquent cho các bảng; phần lớn đặt `$guarded = []`. |
| `app/Mail/` | Email thông báo lead mới. |
| `app/Providers/AppServiceProvider.php` | View composer toàn cục cung cấp `$contact` và `$infor`. |
| `routes/web.php` | Toàn bộ web/admin route thực tế; ưu tiên đọc đầu tiên khi sửa luồng web. |
| `routes/api.php` | Chỉ còn endpoint Sanctum `/api/user` mặc định. |
| `database/migrations/` | Nguồn chuẩn của schema, gồm migration legacy tạo nhiều bảng và migration phase mới. |
| `database/seeders/` | Chỉ seed khóa học mẫu qua `CourseSeeder`; `DatabaseSeeder` gọi seeder này. |
| `resources/views/layouts/` | Layout public `master`, `header`, `footer`. |
| `resources/views/admin/` | Layout/admin sidebar và CRUD Blade. |
| `resources/views/partials/` | JSON-LD Person/Article. |
| `resources/views/vendor/` | Hai template phân trang tùy biến. |
| `public/site/assets/` | Theme public đã build sẵn: CSS, JS, image, fonts. Không sửa vendor/minified trừ khi thật cần. |
| `public/app/assets/` | Theme admin và adapter CKEditor CSRF. |
| `public/media/` | Ảnh chèn từ CKEditor; tạo qua endpoint upload. |
| `storage/app/public/` | File upload quản trị cần được public qua symbolic link `/storage`. |
| `config/` | Cấu hình Laravel chuẩn; lưu ý `auth`, `database`, `filesystems`, `mail`, `session`, `sanctum`. |
| `tests/` | Hiện chỉ có 1 unit example và 1 feature smoke test. |
| `docs/` | Changelog và hướng dẫn deployment/SMTP. |
| `plans/` | Kế hoạch/audit phase C–E, dùng làm bối cảnh lịch sử và các quyết định đã được ghi nhận. |

Không xem `vendor/`, `node_modules/` (nếu được cài), asset minified bên thứ ba hay cache runtime là mã nguồn nghiệp vụ để chỉnh sửa.

## 4. Kiến trúc, quy ước code và bảo mật hiện tại

### Naming và MVC

- Controller dùng PascalCase, nằm theo namespace `App\Http\Controllers` và `App\Http\Controllers\Admin`.
- Model là số ít PascalCase (`Blog`, `Course`, `Lead`); nhiều bảng legacy có tên số nhiều hoặc không chuẩn nên model khai báo `$table` rõ ràng: `setting`, `about`, `blogs`, `services`, `skills`, `images`, `courses`, `leads`, `testimonials`.
- View Blade là tên snake_case ở `resources/views`; admin nhóm theo module, ví dụ `admin/course/index.blade.php`.
- CRUD admin hiện dùng các action `index`, `create`, `edit`, `store`, `delete`. `store` đảm nhiệm cả create và update qua hidden `id` + `updateOrCreate`.
- Không có tầng service. Chỉ đặt logic dùng chung nhỏ ở `Controller::deleteManagedUpload()` để xóa upload do app quản lý.

### Route và middleware

- Web routes dùng group middleware `web` mặc định: cookie/session, CSRF, bindings.
- Toàn bộ `/admin/**` nằm trong `Route::middleware('auth')`.
- Login `POST /login` và lead `POST /lead/store` có `throttle:5,1`.
- API group có throttle `api` 60 request/phút theo user hoặc IP; `/api/user` cần `auth:sanctum`.
- Không có policy/role/permission; mọi user đăng nhập đều có cùng quyền admin. Cần xác minh yêu cầu phân quyền trước khi thêm user/role.

### Validation và dữ liệu đầu vào

- Controller gọi `$request->validate()` trực tiếp; không có Form Request. Khi thêm field phải bổ sung validation ở đúng controller, giữ lỗi quay về form theo mẫu hiện có.
- Upload ảnh thường dùng `nullable|image|max:5120` (5 MB), đọc bằng Intervention Image và lưu `public/images`.
- `images.type` chỉ nhận `0` (Life/Case) hoặc `1` (ảnh trang giới thiệu); giữ validation server-side này khi mở rộng form image.
- Blog/course tự tạo slug bằng `Str::slug()`, thêm `-1`, `-2` khi trùng. Blog **giữ nguyên slug khi edit**; courses có thể tính lại slug từ input/title khi lưu.
- Lead public có honeypot field `website`, validate name/phone/email/message/source_page/course_id và bỏ course id không tồn tại thay vì fail validation.
- Lead chỉ nên dùng một trong năm trạng thái `new`, `contacted`, `qualified`, `won`, `lost` trong `Lead::STATUSES`; controller update hiện mới validate chuỗi `max:50`, vì vậy khi sửa cần tránh tạo status ngoài danh sách này.

### Output HTML và upload

- Nội dung blog/course/profile/setting có chỗ cho phép HTML từ admin. Đặc biệt `code_header`, `code_footer` được render `{!! !!}`; blog detail render HTML content. Chỉ tài khoản admin tin cậy mới được dùng các trường này.
- JSON-LD đã encode bằng các cờ `JSON_HEX_*` để tránh đóng thẻ script từ dữ liệu lưu trữ.
- URL logo/favicon/blog image nhận cả URL tuyệt đối, `/storage/...`, hoặc đường dẫn tương đối; helper Blade chỉ bọc `asset()` với path tương đối. Giữ quy ước này để không tạo URL kép.
- `deleteManagedUpload()` chỉ xóa URL bắt đầu `/storage/`, hạn chế xóa file ngoài vùng upload. Ưu tiên dùng helper này khi bổ sung CRUD có upload.

## 5. Luồng nghiệp vụ chính

### Public site

1. **Trang chủ `/`:** lấy About, services (dùng làm jobs và từ khóa typing), top skills, 3 blog mới, 3 ảnh case, khóa học featured/fallback cùng tối đa 2 khóa học phụ (mỗi course có CTA detail) và tối đa 6 testimonial. Có form tư vấn cuối trang.
2. **Giới thiệu `/about`:** hiển thị About, ảnh `images.type=1`, kỹ năng và dịch vụ.
3. **Portfolio `/portfolio` và life `/life`:** dùng `images.type=0` cho case/life; portfolio kết hợp About, services, skills và ảnh type 1.
4. **Blog:** `/blog` phân trang 12 bài; `/{slug}.html` lấy blog theo slug hoặc 404, rồi lấy 3 bài khác mới nhất.
5. **Khóa học:** `/courses` chỉ hiển thị `is_active=1`, phân trang 12 theo `sort_order ASC, id DESC`; `/courses/{slug}` chỉ xem được khóa active.
6. **Lead:** form trang chủ/contact/course detail gửi `POST /lead/store`; contact cho phép chọn course active (không bắt buộc) và `GET /contact?course={slug}` chỉ preselect slug active. CTA fallback từ course detail và course nổi bật trên home giữ ngữ cảnh này. Hệ thống lọc honeypot, tạo row `leads` trạng thái `new`, thử gửi email admin và luôn trả flash success nếu mail lỗi.
7. **SEO:** layout sinh title/description/canonical/OG/Twitter; sitemap XML gồm trang tĩnh + blog + course active; `robots.txt` chặn `/admin`, `/login` và chỉ sitemap production hiện tại.
8. **Theme:** public có toggle sáng/tối lưu `localStorage['theme']`, ưu tiên lựa chọn trước đó rồi theo `prefers-color-scheme`.

### Admin

1. Guest vào `/admin` bị middleware `auth` chuyển login; login thành công regenerate session, redirect intended hoặc `/admin`; logout là `POST` và invalidate session/regenerate CSRF token.
2. `/admin` là trang setting, không phải dashboard số liệu.
3. Admin cập nhật profile/About và social links; setting site gồm logo/favicon/SEO/snippet header-footer.
4. Blog, course, testimonial, service, skill, image là CRUD qua form admin. Xóa là `POST` có CSRF + confirm UI.
5. Lead list phân trang, có filter query `?status=...`, count theo status và eager-load khóa học; admin cập nhật status/note.
6. CKEditor upload gửi CSRF header tới `POST /admin/ckeditor/image_upload`, nhận JSON URL `asset('media/...')`.

## 6. Database

Migrations hiện có là nguồn schema. Dùng khóa chính auto-increment; đa phần bảng legacy không có foreign key database.

| Bảng | Dữ liệu/chức năng | Quan hệ và lưu ý |
| --- | --- | --- |
| `users` | tài khoản admin: name, email unique, password, remember token, timestamps | Laravel auth mặc định; factory có sẵn. |
| `password_reset_tokens`, `failed_jobs`, `personal_access_tokens`, `jobs`, `cache` | bảng Laravel/framework | `personal_access_tokens` là polymorphic Sanctum; hiện API gần như chưa dùng. |
| `setting` | singleton thực tế row id 1: brand, URL, logo/favicon, snippet header/footer, slogan/note, SEO | `Setting::first()` được inject mọi view. Cần fallback khi bảng rỗng. |
| `about` | singleton thực tế row id 1: name, avatar/image, nội dung, contact, SEO, facebook/instagram/linkedin/x | Migration 2026-07-23 đã thêm 4 cột social nullable. |
| `services` | title, description | dùng làm dịch vụ/jobs và từ typing trên home. |
| `skills` | name, image, description, `number` | home/about sort theo `number`; admin hiện sort index theo id. |
| `images` | `type`, title, image, path, description, content | type `0` = life/case, `1` = ảnh giới thiệu. |
| `blogs` | title, slug, image, description, content, SEO | `slug` có unique index từ migration 2026-07-23-000001. |
| `courses` | title/slug, mô tả/content, thumbnail, giá, thông tin học, CTA, flags, sort, SEO | slug unique; không có quan hệ database bắt buộc với lead. |
| `leads` | course_id nullable, name, phone, email, message, source_page, status, note | Eloquent `belongsTo(Course::class)`; **không có foreign key**, xóa course có thể để lead mồ côi. |
| `testimonials` | người đánh giá, avatar, content, rating, flags, sort | home ưu tiên active + featured, fallback active. |
| `socials` | bảng legacy name/url/logo/slogan | Model/controller đã xóa; bảng vẫn tồn tại, không dùng trong luồng hiện hành. Không tự drop nếu chưa được duyệt. |

### Migration, seeder, factory

- Migrations legacy `2026_01_05_084900_create_database_table.php` tạo `setting`, `about`, `socials`, `services`, `blogs`; `2026_01_06_084049_create_skill_table.php` tạo `skills`, `images`.
- Migrations 2026-03 tạo `courses`, `leads`, `testimonials`.
- Hai migration 2026-07: unique `blogs.slug` và bốn cột social của `about`.
- `DatabaseSeeder` chỉ gọi `CourseSeeder`; seeder sẽ không chèn nếu courses đã có. Không seed Setting/About/User/Testimonial/Blog.
- `UserFactory` có password mặc định `password` cho dữ liệu factory, không phải tài khoản production.
- Theo changelog, DB local có hai blog test `bai-mau-1-facebook-ads`, `bai-mau-2-personal-brand`; cần xác minh dữ liệu trước khi xóa/go-live.

Trước migration: báo rõ rủi ro, backup DB phù hợp môi trường, kiểm tra duplicate/compatibility trước khi thêm unique/index/foreign key. Không dùng `migrate:fresh` trên database có dữ liệu cần giữ.

## 7. Frontend/UI

### Public

- Mọi trang public extends `layouts.master`; master include header/footer, asset theo `asset('site/assets/...')`, Toastr flash và `@stack('scripts')`.
- Các view chính: `home`, `about`, `portfolio`, `life`, `blogs`, `blog_detail`, `courses`, `course_detail`, `contact`, `login`, `errors/404`, `sitemap`. Form `contact` nhận `$courses` active và `$selectedCourse` từ query `course`; giữ course selector optional để không cản trở liên hệ chung.
- `partials/jsonld-person` dùng ở home/about; `jsonld-article` dùng ở blog detail.
- `public/site/assets/css/main.css` là CSS theme; custom của dự án ở `public/site/assets/css/custom.css`, nạp sau main. Không redesign hoặc thay CSS theme lớn nếu chưa có yêu cầu.
- Header có menu desktop `d-none d-lg-block`, offcanvas/hamburger cho mobile, CTA tới `/#final-cta`, theme toggle. Menu `Liên hệ` ở header/footer trỏ thống nhất tới `/contact`; CTA đăng ký học vẫn trỏ tới `/#final-cta` theo luồng form nhanh trên trang chủ.
- Fallback asset/site info dùng `data_get`; kiểm tra fallback khi Setting/About chưa có row. Khi chưa có course active, `/courses` và khu course trên home hiển thị thông báo public cùng CTA tới `/contact`, không lộ hướng dẫn admin. Empty state Case study trên home cũng có CTA tới `/contact`.
- Card Case study trên home dùng mô tả fallback hướng khách truy cập khi record thiếu description; không đưa tên module hay hướng dẫn admin vào public copy.
- Ba form lead public (home, contact, course detail) dùng `autocomplete` cho name/tel/email; phone dùng `type="tel"` và `inputmode="tel"` để hỗ trợ bàn phím mobile. Các field hiển thị có `aria-label`; honeypot vẫn `aria-hidden`. Giữ `name` field và validation server-side hiện có khi sửa form.
- Thông báo gửi lead thành công dùng `role="status"` với `aria-live="polite"`; lỗi validation dùng `role="alert"` ở cả home, contact và course detail. Giữ nguyên copy/thứ tự hiển thị khi sửa feedback form.
- Pagination dùng `resources/views/vendor/paginate.blade.php` hoặc `pagination.blade.php` tùy view, không dùng mặc định Laravel.

### Admin

- Mọi view admin extends `admin.layouts.master`, include `admin.layouts.header`/`sidebar`; base URL dùng `<base href="{{ asset('') }}">`.
- Menu sidebar dùng nhãn tiếng Việt có dấu: Thông tin cá nhân, Hình ảnh, Kỹ năng, Ngành nghề, Bài viết, Khóa học, Leads, Testimonial, Cài đặt.
- Form create/edit có enctype khi upload, `@csrf`; delete là `<form method="POST">` với confirm JS.
- CKEditor CDN ở blog/profile/setting; adapter local `public/app/assets/js/ckeditor-csrf-upload-adapter.js` phải được giữ nếu sửa upload endpoint/CSRF.

## 8. Routes/API

Kết quả `route:list --except-vendor` ngày 24/07/2026: **52 routes** (bao gồm endpoint API). Tóm tắt route nghiệp vụ:

| Nhóm | URI/phương thức | Controller/middleware |
| --- | --- | --- |
| Public tĩnh | `GET /`, `/about`, `/life`, `/portfolio`, `/contact` | `HomeController`; web |
| Blog | `GET /blog`, `GET /{slug}.html` | `BlogController`; catch-all `.html` phải luôn đặt sau route cụ thể |
| Course | `GET /courses`, `GET /courses/{slug}` | `CourseController`; public chỉ lấy active |
| Lead | `POST /lead/store` | `LeadController@store`; `throttle:5,1`, CSRF |
| SEO | `GET /sitemap.xml` | `SitemapController@index` |
| Auth | `GET /login`, `POST /login`, `POST /logout` | `LoginController`; POST login throttle 5/phút |
| Admin | `/admin` và toàn bộ `/admin/{blog,course,lead,testimonial,service,skill,image,...}` | `auth`; CRUD dùng route name trong `routes/web.php` |
| CKEditor | `POST /admin/ckeditor/image_upload` | auth + `SettingController@upload` |
| API | `GET /api/user` | `auth:sanctum`, API throttle; chưa có API business |

Tên route không theo prefix nhất quán hoàn toàn vì legacy: ví dụ public `blog`, admin index `admin.blog`, nhưng action lưu blog là `blog.store`. **Luôn dùng `route('...')`, không tự ghép URI.** Khi thêm route mới, đặt trước `/{slug}.html` nếu có nguy cơ trùng pattern.

## 9. Test: hiện trạng và cách chạy

### Coverage hiện có

- `tests/Unit/ExampleTest.php`: assertion `true` — pass, không cover nghiệp vụ.
- `tests/Feature/ExampleTest.php`: smoke `GET /` dùng `RefreshDatabase`.
- `tests/Feature/LeadStatusValidationTest.php`: admin chỉ cập nhật lead bằng status chuẩn.
- `tests/Feature/CourseDeletionProtectionTest.php`: course có lead được ẩn, course không có lead vẫn xóa được.
- `tests/Feature/AdminImageTypeValidationTest.php`: admin chỉ tạo image với type `0` hoặc `1`.
- `tests/Feature/AdminAccessTest.php`: guest bị chuyển login, user đăng nhập truy cập được route admin đại diện.
- `tests/Feature/LoginLogoutTest.php`: khách xem được trang đăng nhập; người đã đăng nhập bị chuyển về `/admin`; đăng nhập đúng/sai, remember-me và logout admin.
- `tests/Feature/PublicLeadSubmissionTest.php`: lead hợp lệ/honeypot/validation và lỗi email notification không làm mất lead.
- `tests/Feature/SitemapTest.php`: sitemap có blog và course active, không lộ course inactive.
- `tests/Feature/PublicCourseVisibilityTest.php`: danh sách và detail public chỉ hiển thị course active.
- Chưa cover CRUD admin khác, upload, slug, SEO/JSON-LD, phân quyền, hay giao diện responsive.

`phpunit.xml` dùng `DB_CONNECTION=sqlite` và `DB_DATABASE=:memory:`. Feature test có `RefreshDatabase` chạy migrations trong RAM, vì vậy suite không phụ thuộc MySQL/Laragon hoặc `.env` thật. Lần chạy gần nhất: 23 tests, 76 assertions pass. SQLite không thay thế hoàn toàn MySQL production; migration/query đặc thù MySQL vẫn cần kiểm tra phù hợp trước khi dùng.

### Lệnh local

PowerShell hiện không có `php` trong `PATH`; PHP Laragon đã xác minh tại:

```powershell
$php = 'C:\App\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe'
& $php artisan route:list --except-vendor
& $php artisan test
& $php vendor/bin/pint --test
```

SQLite đã là môi trường mặc định của PHPUnit. Chỉ dùng MySQL local để test thủ công khi cần đối chiếu hành vi MySQL; không trỏ test destructive tới DB local/production có dữ liệu.

## 10. Quy trình phát triển tiếp

1. Đọc `AGENTS.md`, phần liên quan của tài liệu này, `git status`, `docs/project-changelog.md`, và route/controller/view/model của chức năng cần đụng.
2. Đọc migration hiện có trước khi kết luận schema hay thêm cột. Nếu cần schema/route cũ/module mới, dừng để xin xác nhận đúng theo `AGENTS.md`.
3. Báo trước phạm vi: thay đổi, file tác động, migration có/không, test tay/lệnh sẽ chạy.
4. Tận dụng flow hiện có; thêm validation, CSRF, auth, fallback dữ liệu và `asset()/route()` theo pattern gần nhất.
5. Nếu có upload, kiểm tra cả trường hợp create, update không upload, update có upload và delete file cũ; không xóa URL external/asset theme.
6. Chạy ít nhất syntax/test phù hợp, `route:list` khi sửa route, test tay màn hình desktop/mobile và lỗi validation. Cập nhật tài liệu/changelog khi thay đổi kiến trúc hoặc quy ước.

Lệnh vận hành local chỉ chạy sau khi nói rõ mục đích. Tham khảo `docs/deployment-guide.md` cho deploy; không deploy chỉ vì hoàn tất code.

### Quy ước vận hành staging/production

- `docs/deployment-guide.md` là checklist triển khai chuẩn. Chỉ đánh dấu một bước là hoàn tất khi có bằng chứng kiểm tra, nhưng không ghi password, token, mailbox hay dữ liệu khách hàng vào Git.
- Chưa có bằng chứng trong repo rằng SMTP thật, mailbox nhận lead, backup/restore hoặc staging/production đã được kiểm tra end-to-end. Đây là việc **cần xác minh** với người sở hữu hạ tầng, không được suy đoán từ test `Mail::fake()`.
- Lead được lưu trước khi gửi `NewLeadNotification`; nếu gửi lỗi, app log lỗi và vẫn báo thành công. Sau khi cấu hình SMTP thật, cần kiểm tra cả hai nhánh: email nhận được khi bình thường và lead vẫn lưu khi SMTP lỗi.
- Log lỗi email lead chỉ dùng event cố định `Lead notification email failed.` cùng `lead_id` và `exception_class`; không ghi raw exception message, credential SMTP hoặc PII của lead vào log.
- Trước deploy: backup DB, xác nhận `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` đúng host, migration phù hợp, storage link và cache. Sau deploy: smoke test public/admin/sitemap/upload/lead theo deployment guide và chuẩn bị cách rollback.

## 11. Checklist thêm tính năng

- [ ] Xác nhận thuộc phase/scope được phép; không kéo theo module không được yêu cầu.
- [ ] Đọc route, controller, model, migration, Blade và asset của flow gần nhất.
- [ ] Xác định có migration hay thay đổi route cũ không; xin duyệt trước nếu có.
- [ ] Có route name, middleware auth/throttle phù hợp và CSRF cho POST/PUT/DELETE.
- [ ] Validate server-side toàn bộ input; không chỉ dựa vào JS/client.
- [ ] Dùng `firstOrFail`/scoping phù hợp để trả 404 thay vì null error.
- [ ] Dùng `route()` cho URL nội bộ, `asset()` đúng quy ước URL upload/asset.
- [ ] Dữ liệu trống có fallback lịch sự, không vỡ view.
- [ ] Kiểm tra desktop/mobile, flash success/error, upload (nếu có), quyền guest/admin.
- [ ] Bổ sung test nếu chạm luồng có rủi ro; nêu rõ nếu chưa cover.
- [ ] Cập nhật tài liệu này nếu phát sinh bảng, route group, convention hay rủi ro mới.

## 12. Checklist sửa bug

- [ ] Tái hiện lỗi và ghi URI, role, dữ liệu, log/stack trace; không chỉ sửa theo suy đoán.
- [ ] Khoanh vùng controller/view/model/config/migration liên quan trước khi edit.
- [ ] Kiểm tra có phải lỗi môi trường (DB, storage link, cache, `.env`) hay không.
- [ ] Đề xuất bản sửa nhỏ nhất, giữ route/schema/UI legacy nếu không cần đổi.
- [ ] Kiểm tra regression ở luồng create/edit/delete hoặc public/admin liên quan.
- [ ] Với lỗi upload: test file mới, giữ file cũ khi không upload, và xóa file cũ đúng vùng `/storage`.
- [ ] Với lỗi SEO/public: kiểm tra source HTML, canonical/OG, ảnh fallback và URL nested `/courses/{slug}`.
- [ ] Ghi rõ nguyên nhân, bằng chứng, cách test và rủi ro còn lại trong báo cáo/commit.

## 13. QC trước commit

- [ ] `git diff --check` sạch; không có file cache, log, `.env`, upload thử, `vendor`/asset build không chủ đích.
- [ ] Diff đúng scope, không refactor/xóa hàng loạt ngoài yêu cầu.
- [ ] Route thay đổi: chạy `artisan route:list`; route catch-all không che route mới.
- [ ] PHP: chạy test/Pint phù hợp nếu môi trường sẵn sàng; nêu chính xác test nào không chạy và lý do.
- [ ] Form: CSRF, validation, error/old input, auth/throttle phù hợp.
- [ ] Xem tay 360px, 768px, desktop cho UI public sửa đổi; không 404 asset và không lỗi console.
- [ ] Kiểm tra guest không vào admin, admin thao tác CRUD đúng; logout vẫn POST.
- [ ] Migration (nếu được duyệt): backup/kiểm tra dữ liệu trước chạy, có `down()`, không tác động dữ liệu ngoài dự kiến.
- [ ] Không làm lộ secrets trong log/diff/tài liệu; không commit `.env`.

## 14. Rủi ro kỹ thuật và điểm cần cẩn thận

1. **Khác biệt SQLite/MySQL (trung bình):** PHPUnit đã cô lập bằng SQLite in-memory, nhưng test không thay thế hoàn toàn hành vi MySQL production (schema/query/encoding đặc thù). Kiểm tra MySQL khi phase có rủi ro tương thích.
2. **Coverage còn giới hạn (trung bình):** đã cover smoke, lead và bảo vệ xóa course; vẫn chưa bảo vệ auth, CRUD admin khác, upload, sitemap/SEO và UI.
3. **Thiếu foreign key (trung bình):** `leads.course_id` nullable nhưng không constrained. Xóa course có thể làm lead còn course id không tồn tại; admin có fallback `Course #id`.
4. **Mass assignment rộng (trung bình):** hầu hết model `$guarded = []`. Controller hiện chọn field qua `only()`, nhưng code mới không được `create($request->all())`.
5. **HTML/snippet từ DB (trung bình/cao theo quyền admin):** `code_header`, `code_footer`, rich content được render raw. Đây là chủ đích CMS nhưng phải giới hạn quyền admin, không lấy dữ liệu từ public input vào các field đó.
6. **Upload CKEditor ra `public/media` (trung bình):** validation chấp nhận ảnh cụ thể, nhưng không qua `Storage`/helper xóa upload; file chèn rich text chưa có cơ chế dọn orphan. Không tự xóa nếu chưa có strategy tham chiếu.
7. **Email lead (trung bình):** lỗi mail bị nuốt có log để không cản lưu lead. Cần cấu hình `MAIL_*` và `LEAD_NOTIFY_EMAIL` thật trước production; local Mailpit không gửi ra ngoài.
8. **Singleton dữ liệu (trung bình):** nhiều view/controller dùng `About::first()`/`Setting::first()` và một số update cố định id 1. DB rỗng/row id khác 1 cần xác minh trước khi thao tác data/migration.
9. **View composer toàn cục (thấp/trung bình):** mỗi request query About/Setting một lần nhờ static memoization trong request. Tốt hơn trước nhưng vẫn query mọi view/request; chưa cache xuyên request để tránh stale data admin.
10. **Asset legacy/CDN (trung bình):** public/admin phụ thuộc nhiều asset static và CDN (CKEditor, Toastr). Khi đổi CSP/offline/deploy, cần kiểm tra kỹ. Vite không phải pipeline chính của UI hiện tại.
11. **Tài liệu phase lỗi thời (thấp):** các plan vẫn `Pending`, phải đối chiếu changelog và source.
12. **Production (cần xác minh):** trước deploy cần `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` đúng host, migrations đã chạy, `storage:link`, cache config/route/view. Xem chi tiết trong `docs/deployment-guide.md`.

## 15. Những việc còn thiếu để dự án hoàn thiện hơn

Các việc dưới đây là đề xuất tồn đọng, **không tự triển khai khi chưa được duyệt**:

- Thiết lập database testing cô lập và thay example tests bằng coverage cho auth, public routes, lead, CRUD, slug, upload và sitemap.
- Cấu hình SMTP/`LEAD_NOTIFY_EMAIL` production, sau đó test email end-to-end.
- Bổ sung ràng buộc/chiến lược giữ lịch sử lead khi course bị xóa (foreign key, `nullOnDelete`, hoặc chặn delete) — cần migration và quyết định nghiệp vụ.
- Chuẩn hóa Form Request, `$fillable`, validation trạng thái lead; là refactor có phạm vi nên cần kế hoạch riêng.
- Quyết định phân quyền/roles nếu có nhiều admin; hiện một guard cho toàn bộ quyền.
- Xác định lifecycle cho `public/media` CKEditor và ảnh orphan; tránh dọn tự động có nguy cơ làm mất ảnh đang được dùng trong rich content.
- Rà soát accessibility/SEO bằng trình duyệt thực tế (menu keyboard, alt text, contrast, responsive) và hiệu năng asset/caching sau khi có baseline đo đạc.
- Rà soát/xóa blog mẫu trước go-live nếu không cần, sau khi backup và xác minh dữ liệu.
