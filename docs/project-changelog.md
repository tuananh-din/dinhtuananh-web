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

---

**Ghi chú:** DB local có 2 blog seed test (`bai-mau-1-facebook-ads`, `bai-mau-2-personal-brand`) — xoá trước khi go-live nếu không cần.
