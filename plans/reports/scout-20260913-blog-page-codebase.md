# Audit kỹ thuật `/blog` — 13/09/2026

## Kết luận nhanh

View thực tế là `resources/views/blogs.blade.php`, không phải `resources/views/blog/index.blade.php`. Luồng dữ liệu đã an toàn ở mức cơ bản: chỉ lấy bài đã đăng, eager-load chuyên mục, filter/search giữ query khi phân trang và newsletter có lưu subscriber thật. Điểm cần ưu tiên là làm rõ giá trị Blog ngay đầu trang, hoàn thiện SEO riêng cho listing, tránh chuyên mục rỗng và cải thiện semantics/responsive của filter, card, pagination.

Production `https://dinhtuananh.com/blog` trả HTTP 200 khi kiểm tra header; không lấy được HTML body ổn định từ môi trường audit, nên các nhận định visual dựa trên Blade/CSS hiện hành.

## Những phần đang đúng

- Public route rõ ràng: `GET /blog`, route name `blogs`; detail giữ URL cũ `/{slug}.html` ([routes/web.php:59,71](../../routes/web.php)).
- Listing chỉ query `is_published = 1`, eager-load `categories`, phân trang 12 item và giữ `search/category/page` qua `withQueryString()` ([BlogController.php:12-16](../../app/Http/Controllers/BlogController.php)).
- M:N Blog–Category đã có unique pivot và foreign key; Blog không có relation/tag/author model nào khác ([Blog.php:17-20](../../app/Models/Blog.php), [Category.php:11-14](../../app/Models/Category.php), [migration category:19-25](../../database/migrations/2026_08_04_000004_create_categories_and_blog_category_tables.php)).
- Card đang có ảnh lazy-load, alt từ title, ngày tạo và category thực tế ([blogs.blade.php:43-61](../../resources/views/blogs.blade.php)).
- Newsletter không phải form giả: POST bị throttle, lưu email unique vào `subscribers`, thử sync Brevo nhưng lỗi Brevo không làm mất đăng ký ([routes/web.php:63](../../routes/web.php), [NewsletterController.php:18-57](../../app/Http/Controllers/NewsletterController.php), [footer.blade.php:25-34](../../resources/views/layouts/footer.blade.php)). Đã có feature test cho lưu và duplicate ([NewsletterSubscriptionTest.php:13-32](../../tests/Feature/NewsletterSubscriptionTest.php)).
- Layout đã có canonical, OG cơ bản, RSS, skip link; navigation đánh dấu Blog đang active ([master.blade.php:35-57,116-124](../../resources/views/layouts/master.blade.php), [header.blade.php:95-100](../../resources/views/layouts/header.blade.php)).

## Vấn đề và khuyến nghị

| Ưu tiên | Vấn đề cụ thể | Ảnh hưởng | Hướng sửa tối thiểu, an toàn |
|---|---|---|---|
| High | H1 chỉ là `Blog`; không có intro/hero diễn giải chủ đề ([blogs.blade.php:15](../../resources/views/blogs.blade.php)). CSS làm H1 100px, uppercase và căn giữa ([main.css:10918-10937](../../public/site/assets/css/main.css)). | Không trả lời “blog này dành cho ai/nói về gì” trong 5 giây; mobile tốn chiều cao nhưng không tạo giá trị. | Đổi thành một H1 mô tả “Digital Marketing, dữ liệu và triển khai thực tế”; thêm 1 đoạn ngắn đúng brand. Không thêm số liệu, bài viết hay proof giả. |
| High | Blog listing không set `meta_description`, `og_description`, `og_image` hay JSON-LD riêng; hiện nhận fallback SEO chung của site từ master ([blogs.blade.php:1-4](../../resources/views/blogs.blade.php), [master.blade.php:3,35,40-56](../../resources/views/layouts/master.blade.php)). | Snippet/preview mạng xã hội mơ hồ; OG image có thể hoàn toàn thiếu nếu Setting không có `og_image`. | Khai báo title/descriptions listing ngay trong Blade; chỉ dùng OG image global nếu CMS đã có. Không thêm ảnh mới/dữ liệu giả. |
| High | Sitemap công bố các URL `?category=...` ([sitemap.blade.php:26-29](../../resources/views/sitemap.blade.php)) nhưng canonical global dùng `url()->current()` và loại query ([master.blade.php:56](../../resources/views/layouts/master.blade.php)). | Category URL trong sitemap canonical về `/blog`, tín hiệu index mâu thuẫn. | Khi category hợp lệ, set canonical self URL trong `blogs.blade.php`; search nên canonical `/blog`. Cần quyết định trước có muốn category filter là landing indexable hay chỉ là faceted navigation. |
| Medium | Controller lấy mọi category kể cả category không có bài published; filter có thể dẫn tới empty state dù user vừa chọn option ([BlogController.php:15-16](../../app/Http/Controllers/BlogController.php)). | Khám phá chủ đề kém tin cậy, thêm dead-end. | Lấy categories bằng `whereHas('blogs', is_published=1)`; giữ sort name. Không cần migration/route. |
| Medium | Search chỉ tìm title/description, không tìm content/category; field chưa là `type=search`, placeholder chung chung ([BlogController.php:15](../../app/Http/Controllers/BlogController.php), [blogs.blade.php:16-24](../../resources/views/blogs.blade.php)). | User không biết phạm vi tìm; nhiều bài không có description sẽ khó được tìm thấy. | UI: `type=search`, placeholder cụ thể hơn, reset filter rõ. Logic content search chỉ thêm khi product xác nhận đây là kỳ vọng (LIKE trên content là query nặng và không có full-text index). |
| Medium | Card không dùng `<article>`, title là H3 ngay sau H1 (không có H2 section), description bị bỏ trống khi CMS không nhập; card không có flex/equal-height ([blogs.blade.php:39-64](../../resources/views/blogs.blade.php), [main.css:10750-10793](../../public/site/assets/css/main.css)). | Heading hierarchy/scanability kém; grid có thể lệch CTA với data dài/ngắn. | Thêm H2 cho khu vực kết quả, dùng `<article>`, fallback excerpt từ `strip_tags(content)` khi description rỗng, CSS `.blog-page` để content card flex-column và CTA ở cuối. |
| Medium | `image_url` trả ảnh thumbnail generic nếu `image` rỗng ([Blog.php:22-33](../../app/Models/Blog.php)); card luôn gắn alt bằng title ([blogs.blade.php:43-46](../../resources/views/blogs.blade.php)). | Thumbnail generic không đại diện nội dung nhưng screen reader lại hiểu là ảnh của bài viết; không có width/height HTML để reserve image size. | Chỉ alt title cho ảnh bài viết thật; fallback generic dùng `alt=""` hoặc hiển thị placeholder không phải nội dung. CSS đã giữ 3:2/object-fit ([custom.css:353-362](../../public/site/assets/css/custom.css)); thêm `decoding="async"` và kích thước khi biết asset ratio. |
| Medium | Metadata hiện có category + `created_at`, nhưng không có author/tag/published_at trong schema/model ([blogs.blade.php:49-55](../../resources/views/blogs.blade.php), [base migration:64-75](../../database/migrations/2026_01_05_084900_create_database_table.php)). | Không được phép tự hiển thị author/tag hoặc gọi created_at là “ngày xuất bản” nếu CMS không lưu dữ liệu đó. | Giữ category/date hiện có, có thể gọi trung tính “Cập nhật” nếu cần; không tạo model/schema trong scope. |
| Medium | Pagination template chỉ là `<ul>`, thiếu landmark/aria label, `aria-current`, text cho icon buttons; dấu …/disabled/current đều là anchor không có href ([vendor/paginate.blade.php:1-37](../../resources/views/vendor/paginate.blade.php)). | Keyboard/screen reader không hiểu trạng thái và hành động điều hướng. | Trong scope listing có thể bọc `links()` bằng `<nav aria-label="Phân trang bài viết">`; sửa semantics hoàn chỉnh cần sửa `resources/views/vendor/paginate.blade.php` (ngoài technical scope đã nêu). |
| Low | CSS Blog hiện dựa selector template chung (`news-*`), không có namespace page; dark-mode chỉ style select, không có layout/touch behavior riêng cho form ([custom.css:353-362,1133-1144](../../public/site/assets/css/custom.css)). | Khó đảm bảo 1440/768/375, dễ side effect ở trang News template khác. | Set `@section('body_class','blog-page')`; thêm CSS dưới `.blog-page` cho hero, toolbar grid, button, card, focus-visible và breakpoints 768/375. |
| Low | Footer chỉ hiện social có dữ liệu (đúng), nhưng luôn render `tel:`/`mailto:` dù dữ liệu rỗng ([footer.blade.php:37-68,74-77](../../resources/views/layouts/footer.blade.php)). | Link trống nếu CMS chưa xác nhận contact. | Chỉ sửa nếu được mở scope layout; không cần chạm cho task Blog. |

## Scope implement đề xuất

Không migration, không route, không dữ liệu mẫu. Chỉ cần:

1. `app/Http/Controllers/BlogController.php`: giới hạn dropdown category vào những category có bài public; giữ search/category/pagination hiện tại.
2. `resources/views/blogs.blade.php`: body class, metadata/canonical phù hợp, hero copy, toolbar search/filter dễ hiểu, H2 result area, semantic card/excerpt fallback, alt fallback đúng, CTA nhẹ sang `route('courses')` ở cuối listing nếu team duyệt. Newsletter giữ ở footer vì backend đã có.
3. `public/site/assets/css/custom.css`: chỉ các selector `.blog-page ...` và responsive desktop/tablet/mobile; không sửa CSS global.
4. Tests nên bổ sung cho: metadata/canonical blog/category, chỉ show category có bài public, search/category giữ query qua pagination và empty state. Test hiện chỉ cover draft + empty state ([PublicSeoJsonLdTest.php:135-164](../../tests/Feature/PublicSeoJsonLdTest.php)).

Không nên thêm “featured/popular/related” ở listing: controller không có dữ liệu ranking/feature, thêm sẽ tạo khối nội dung không có contract. Related thật đã tồn tại ở detail qua `otherBlogs`, nhưng hiện chỉ là newest, chưa phải cùng category ([BlogController.php:19-26](../../app/Http/Controllers/BlogController.php)).

## Câu hỏi chưa giải quyết

1. Category URL `?category=slug` có chủ đích index trên Google không? Sitemap hiện nói là có, canonical hiện nói là không.
2. Có chấp nhận search cả `content` không, hay chỉ title/description để giữ hiệu năng DB?
3. `created_at` được xem là ngày xuất bản đáng tin hay cần chỉ hiển thị khi CMS có `published_at`?
4. Setting production đã có `og_image` và Brevo credentials chưa? Code có hỗ trợ, audit không đọc `.env`/dữ liệu production.
