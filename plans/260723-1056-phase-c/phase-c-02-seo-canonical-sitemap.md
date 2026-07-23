# C-02: SEO kỹ thuật — canonical + sitemap.xml + robots.txt

## Overview
- **Priority:** P0
- **Status:** Pending
- **Migration:** Không
- Phase A-4 đã có title/meta/OG nhưng còn thiếu 3 thứ SEO kỹ thuật cơ bản cho personal brand site: thẻ `<link rel="canonical">`, `sitemap.xml`, và robots.txt trỏ tới sitemap.

## Key Insights (từ đọc code)
- `resources/views/layouts/master.blade.php`: có `og:url` (dòng 33) nhưng KHÔNG có canonical link.
- `public/robots.txt` hiện chỉ có `User-agent: * / Disallow:` — chưa có dòng `Sitemap:`.
- Chưa có route/file sitemap nào (đã grep, không thấy).
- Nội dung động cần vào sitemap: 5 trang tĩnh (`/`, `/about`, `/portfolio`, `/blog`, `/courses`, `/contact`, `/life`), blog theo route `/{slug}.html` (name `blog`), course theo `/courses/{slug}` (chỉ `is_active=1`).
- Model `Blog` không có cột `is_active` → đưa toàn bộ blog vào sitemap.
- Route `/{slug}.html` là catch-all cuối file — route `/sitemap.xml` phải khai báo TRƯỚC nó (có `.xml` nên không match `{slug}.html`, nhưng vẫn đặt trên cho rõ ràng).

## Requirements
- Canonical URL trên mọi trang public (mặc định `url()->current()`, trang blog/course detail dùng route chuẩn).
- `GET /sitemap.xml` trả XML gồm trang tĩnh + toàn bộ blog + course active, có `lastmod` từ `updated_at`.
- `robots.txt` thêm dòng `Sitemap: {url}/sitemap.xml` và `Disallow: /admin`, `Disallow: /login`.

## Related Code Files
- **Sửa:** `resources/views/layouts/master.blade.php` (thêm 1 dòng canonical trong `<head>`), `routes/web.php` (thêm 1 route), `public/robots.txt`
- **Tạo:** `app/Http/Controllers/SitemapController.php`, `resources/views/sitemap.blade.php` (view XML)
- **Xóa:** Không

## Implementation Steps
1. Thêm vào `<head>` của master.blade.php (cạnh og:url): `<link rel="canonical" href="@yield('canonical', url()->current())">`.
2. Tạo `SitemapController@index`: query `Blog::select('slug','updated_at')->get()` + `Course::where('is_active',1)->select('slug','updated_at')->get()`, trả `response()->view('sitemap', [...])->header('Content-Type','text/xml')`.
3. Tạo view `sitemap.blade.php` xuất `<urlset>` chuẩn sitemap protocol: trang tĩnh (dùng `route()` name có sẵn) + blog (`route('blog', $slug)`) + course (`route('course.detail', $slug)`).
4. Thêm route `Route::get('/sitemap.xml',[SitemapController::class,'index'])->name('sitemap');` — đặt TRƯỚC dòng route `blog` catch-all.
5. Cập nhật `public/robots.txt`:
   ```
   User-agent: *
   Disallow: /admin
   Disallow: /login
   Sitemap: https://{domain}/sitemap.xml
   ```
   (Lưu ý: robots.txt là file tĩnh, không dùng được `url()` — ghi domain thật; hỏi user domain production, tạm dùng URL local khi test.)

## Todo
- [ ] Thêm canonical vào master.blade.php
- [ ] Tạo SitemapController + view sitemap.blade.php
- [ ] Thêm route /sitemap.xml (trước catch-all)
- [ ] Cập nhật robots.txt
- [ ] Test tay theo Success Criteria

## Success Criteria (test tay)
1. View-source trang chủ, `/blog`, 1 bài blog → thấy đúng 1 thẻ `<link rel="canonical">` với URL hiện tại.
2. Mở `/sitemap.xml` → XML hợp lệ (browser render cây XML), chứa đủ trang tĩnh + 2 blog seed + course active, không lỗi 500 khi DB course rỗng.
3. Mở `/ten-bai-blog.html` vẫn hoạt động bình thường (route catch-all không bị ảnh hưởng).
4. `/robots.txt` hiển thị nội dung mới.

## Risk Assessment
- **Thấp-trung bình.** Rủi ro chính: route mới đặt sai vị trí so với catch-all `/{slug}.html` → test kỹ mục 3. `.xml` khác `.html` nên về lý thuyết không đụng nhau.
- Sitemap query toàn bộ blog — hiện chỉ 2 bài, không vấn đề hiệu năng (YAGNI: chưa cần cache).
- Rollback: xóa route + controller + view, revert robots.txt.

## Security Considerations
- Sitemap chỉ expose URL public, không expose admin.
- robots.txt Disallow `/admin`, `/login` — giảm bị index trang quản trị (không phải biện pháp bảo mật, chỉ SEO hygiene).
