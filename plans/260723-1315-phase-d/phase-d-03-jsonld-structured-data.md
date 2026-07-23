# D-03 — JSON-LD structured data: Article (blog) + Person (home/about)

## Overview

- **Priority:** P1
- **Status:** Pending (không cần xác nhận — chỉ thêm markup, không đổi schema/route)
- **Migration:** KHÔNG
- Nối tiếp C-02 (canonical/sitemap). Giúp Google hiểu bài viết + nhận diện thương hiệu cá nhân (rich result).

## Key Insights (đã verify từ code)

- Grep `ld+json` toàn bộ `resources/views` → 0 kết quả: chưa có structured data nào.
- `resources/views/layouts/master.blade.php` — head có `@yield('canonical')`, OG tags (dòng 25-34) nhưng KHÔNG có `@stack('head')` → cần thêm điểm chèn cho JSON-LD.
- `resources/views/blog_detail.blade.php:2-16` — đã có sẵn `$blogDescription`, `$blog->title`, `$blog->image`, `$blog->created_at/updated_at` → đủ dữ liệu cho `Article`.
- View composer bơm `$infor` (Setting) cho mọi view; bảng `about` có `name, avatar, email, tel, address, about_me` (migration `2026_01_05_084900:30-46`) → đủ cho `Person`. Cần xem `HomeController::about` truyền `$about` như thế nào trước khi code.
- Bảng `socials` có `url` → có thể dùng cho `sameAs` nếu composer/controller đã load socials (verify khi code, nếu chưa load thì bỏ qua — KHÔNG thêm query mới chỉ vì sameAs).

## Requirements

1. `Article` JSON-LD trên trang blog detail: headline, description, image, datePublished, dateModified, author (Person), mainEntityOfPage (canonical URL).
2. `Person` JSON-LD trên trang chủ và `/about`: name, url, image (avatar), jobTitle/description nếu có; `sameAs` từ socials nếu dữ liệu đã sẵn.
3. Fallback lịch sự: field null thì bỏ khỏi JSON (không in `null`), không làm vỡ trang khi thiếu dữ liệu.
4. Output qua `@json()` / `json_encode` với `JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES` — không nối chuỗi tay (tránh vỡ JSON vì dấu nháy).

## Related Code Files

- **Sửa:** `resources/views/layouts/master.blade.php` (thêm `@stack('structured_data')` trước `</head>`)
- **Tạo:** `resources/views/partials/jsonld-article.blade.php`, `resources/views/partials/jsonld-person.blade.php` (build mảng PHP → `@json`)
- **Sửa:** `resources/views/blog_detail.blade.php` (push Article), `resources/views/home.blade.php` + `resources/views/about.blade.php` (push Person)
- **Không đụng:** controllers (dùng data đã có sẵn trong view; nếu thiếu → báo lại, không tự thêm query)

## Implementation Steps

1. Thêm `@stack('structured_data')` vào master head.
2. Tạo partial `jsonld-article.blade.php`: nhận `$blog`, build mảng schema.org Article, lọc field rỗng bằng `array_filter`, in `<script type="application/ld+json">@json($schema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)</script>`.
3. `blog_detail.blade.php`: `@push('structured_data') @include('partials.jsonld-article') @endpush`.
4. Verify data trang home/about (biến `$about`/`$infor` thực tế), tạo partial `jsonld-person.blade.php` tương tự, push vào `home.blade.php` + `about.blade.php`.
5. Test tay + validator.

## Todo

- [ ] `@stack('structured_data')` trong master
- [ ] Partial Article + push ở blog_detail
- [ ] Verify biến trang home/about
- [ ] Partial Person + push ở home, about
- [ ] Validate bằng Google Rich Results Test / validator.schema.org (paste HTML)

## Success Criteria

- `http://127.0.0.1:8000/{slug}.html` → view-source thấy 1 block `application/ld+json` Article, JSON parse hợp lệ (paste vào validator.schema.org: 0 error).
- `http://127.0.0.1:8000/` và `/about` → block Person hợp lệ.
- Blog thiếu image/description → JSON vẫn hợp lệ, không có key rỗng, trang không lỗi.
- Không ảnh hưởng render/level layout (JSON-LD là script inert).

## Risk Assessment

- **JSON vỡ do ký tự đặc biệt trong content** (khả năng: thấp) → Mitigation: bắt buộc `@json`, không nối chuỗi.
- **Dữ liệu About thiếu (avatar/email null)** → Mitigation: `array_filter` bỏ field rỗng; schema tối thiểu chỉ cần `@type` + `name`.
- **Rollback:** revert commit (chỉ views, zero side effect).

## Security Considerations

- `@json` tự escape → không XSS từ content bài viết vào script tag.
- Không đưa `tel`/`email` cá nhân vào Person nếu user không muốn public — hỏi user khi code (mặc định: bỏ tel, giữ email nếu đã hiển thị sẵn trên trang contact).
