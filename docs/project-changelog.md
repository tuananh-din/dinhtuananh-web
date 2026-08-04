# Changelog — tanh (Personal Brand, Laravel)

Ghi các thay đổi quan trọng theo phase. Mỗi mục 1 commit. Nhánh: `master`.

## Phase E — 2026-07-23 (fix tồn đọng audit + bug P0 social + build-out)

- `8af7e7a` **E-01** `RouteServiceProvider::HOME` `/home` → `/admin` (hết 404 khi guest đã đăng nhập mở `/login`).
- `5a4e764` **E-02** JSON-LD 2 partial thêm `JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT` — chặn stored XSS admin→public.
- `e74b71a` **E-03** CKEditor upload bỏ `?_token=` trong URL, dùng custom adapter (`public/app/assets/js/ckeditor-csrf-upload-adapter.js`) gửi header `X-CSRF-TOKEN`.
- `775b0eb` **E-04** Dọn trang login: title theo brand, nền qua `asset()`, bỏ link chết + footer template, thêm checkbox "Ghi nhớ đăng nhập".
- `be2a7dd` **E-05 (P0)** Migration `2026_07_23_000002_add_social_columns_to_about_table` thêm 4 cột `facebook/instagram/linkedin/x` (string 191, nullable) vào bảng `about` + validate `nullable|url|max:255` — **sửa lỗi 500 khi lưu admin profile**.
- `7ed7fa9` **E-06** JSON-LD Person thêm `sameAs` từ 4 URL social (bỏ khi rỗng).
- `3b36234` **E-08** Admin lead: `Lead::STATUSES` + relation `course()`; lọc theo trạng thái, đếm theo trạng thái, hiện tên khoá học thay Course ID.
- `24cdfa1` **E-09** `og:type=article` cho blog detail + 4 thẻ `twitter:*` toàn site.
- `7a4d969` **E-07** Xoá dead code `SocialController` + model `Social` (GIỮ bảng `socials`).

## Phase D — 2026-07-23 (SEO structured data, bảo mật, email lead)

- `ada64fe` D-04 throttle login (`throttle:5,1`).
- `118dfd5` D-01 unique slug blogs (migration `add_unique_index_to_blogs_slug`), giữ slug khi edit.
- `f19dc39` D-02 admin delete GET→POST 6 module + `@csrf` + null-safe.
- `0d7a9fb` D-03 JSON-LD `@stack('structured_data')` + partial article/person.
- `c7251c8` D-05 email lead: Mailable `NewLeadNotification` + config `mail.lead_notify` (mail fail không chặn lưu lead). **Cần cấu hình SMTP thật + `LEAD_NOTIFY_EMAIL` — xem deployment-guide.**

## Phase C — 2026-07-23 (SEO on-page + cleanup)

- `13922b9` C-01 throttle lead form. `0557b9b` C-02 canonical + sitemap.xml + robots.txt. `82f4287` C-03 trang 404 brand. `2c...` C-04 tách inline CSS. `9aa98a0` C-05 accessor `image_url`. `9bb80eb` C-06 memoize view composer.

## Audit toàn codebase + fix A1–A9 — 2026-07-23 (lỗi legacy gốc)

- A1 (CRITICAL) chặn RCE upload CKEditor. A2 (HIGH) sửa cột `code_footer`. A3 (HIGH) `findOrFail` 6 admin edit. A4/A5 validate store + rule image. A6 giữ path khi edit không upload. A7/A8 UI logout POST + honeypot lead. A9 helper xoá file rác conservative.

## Phase A + B — 2026-07-22 (nền tảng public + SEO cơ bản)

Trang public (home/about/courses/blog), meta SEO/OG cơ bản, responsive.

## Phase F — 2026-07-30

- `f4403ed` **F-5** Cập nhật README theo trạng thái project.
- `ae3d147` **F-6** Đánh dấu hoàn tất các kế hoạch Phase C, D và E.

## Phase G — 2026-07-30 (email lead thật)

- `34d5a05` **G-1** Thêm command `lead:test-notify` để kiểm tra gửi email lead không lưu dữ liệu.
- `0056dd8` **G-2** Cập nhật Mailable thông báo lead.
- `2585901` **G-3** Bổ sung test cho luồng thông báo email lead.
- `72c8385` **G-4** Cập nhật hướng dẫn Gmail SMTP và kiểm tra email lead khi deploy.

## Phase H — 2026-08-04 (Admin + đồng bộ schema)

- `4bb18f1` **H-0** Migration idempotent bổ sung `code_footer` cho bảng `setting`.
- `e6018e9` **H-1** Cập nhật hướng dẫn deploy cho cấu hình production.
- `93a7590` **H-2** Chuẩn hóa thông báo flash sau khi lưu các mục Admin.
- `c44cf30` **H-3** Thêm Dashboard Admin và route `/admin`.
- `9ef905e` **H-4** Quản lý Lead: tìm kiếm, xuất CSV và xóa mềm bằng `deleted_at`.

## Auto-deploy cPanel — 2026-08-04

- `f19fc34` Thêm `.cpanel.yml` cho cPanel Git Version Control deploy và cập nhật `.gitignore` cần thiết.

## NHÓM 1 — 2026-08-04 (thu lead + chuyển đổi)

- `a7131e7` **1.1** Thêm bảng `subscribers`, form newsletter và đồng bộ Brevo non-blocking.
- `6e30cdf` **1.2** Thêm trang cảm ơn `/cam-on` và `@stack('conversion')` cho mã chuyển đổi.
- `1da1279` **1.3** Thêm lead magnet, bảng `lead_magnets`, gửi email tải tài liệu và CRUD Admin.
- `6806aff` **1.4** Bổ sung Dashboard phân tích lead theo nguồn, khoảng thời gian và trạng thái.

## NHÓM 2 — 2026-08-04 (SEO + traffic)

- `4feabf7` **2.1** Thêm JSON-LD Course cho trang chi tiết khóa học.
- `45812f5` **2.2a** Thêm bảng `categories`, pivot `blog_category` và quan hệ Blog/Category.
- `a3fa6b2` **2.2b** Thêm CRUD Chuyên mục trong Admin và menu sidebar.
- `da7a856` **2.2c** Form Blog chọn nhiều chuyên mục, lưu bằng `sync()`.
- `f6cee15` **2.2d** Thêm tìm kiếm title/description và lọc blog public theo chuyên mục.
- `7455c82` **2.3** Thêm JSON-LD BreadcrumbList cho blog và khóa học.
- `1955d76` **2.4** Thêm `loading="lazy"` cho ảnh nội dung công khai.

## NHÓM 3 — 2026-08-04 (accessibility + branding Admin)

- `e78a6a1` **3.2** Thêm skip-to-content, focus-visible, aria/alt cơ bản và cải thiện accessibility trang public.
- `8412766` **3.3** Đồng bộ branding Admin theo nhận diện site bằng CSS và asset có sẵn.

## Review fix — 2026-08-04

- `0ded470` Đồng bộ subscriber lead magnet lên Brevo theo cách non-blocking; bỏ branding trùng và bổ sung `.visually-hidden`.

## NHÓM 4 — 2026-08-04 (vận hành)

- `47571a0` **health** Thêm route công khai `/health`: kiểm tra DB, trả JSON `ok` (200) hoặc `error` (503).
- `8175ec2` **backup-db** Thêm command `backup:db`: `mysqldump` qua `MYSQL_PWD`, nén vào `storage/app/backups`, dọn bản quá 14 ngày; sẵn dùng với cron hằng đêm.

---

**Ghi chú:** DB local có 2 blog seed test (`bai-mau-1-facebook-ads`, `bai-mau-2-personal-brand`) — xoá trước khi go-live nếu không cần.
