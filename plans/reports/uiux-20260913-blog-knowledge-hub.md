# UX/UI + Content + SEO brief — Blog knowledge hub

**Ngày:** 2026-09-13
**Phạm vi đề xuất:** `/blog` — giữ Laravel/Blade, route và schema hiện tại. Không tạo dữ liệu giả.

## Kết luận

Trang hiện có nền tảng đúng: chỉ lấy bài đã đăng, tìm kiếm + chuyên mục dùng query string, card có ảnh/date/category/mô tả và newsletter footer có backend thật. Điểm cản lớn nhất là H1 `Blog` rất lớn nhưng không nói người đọc nhận được gì; form lọc lẫn vào nội dung; card chưa có nhịp đọc và không reserve kích thước ảnh. Đổi `/blog` từ “lưới bài viết của theme” thành một **knowledge hub biên tập, gọn và đáng tin**, không biến thành sales page.

## Nguồn xác minh

- View thật là `resources/views/blogs.blade.php`, không có `resources/views/blog/index.blade.php`; card, search/filter nằm inline, không có component riêng.
- `BlogController::blogs()` chỉ trả bài `is_published`, eager-load categories, search `title`/`description`, lọc `category`, `paginate(12)->withQueryString()`.
- Schema Blog có `title`, `slug`, `image`, `description`, `content`, SEO fields, timestamps, `is_published`; **không có** author, tag, featured/popular/view count hoặc published_at.
- `NewsletterController::store()` thực sự lưu `Subscriber` (unique theo email) và sync Brevo chỉ khi cấu hình có sẵn. Form này hiện ở `layouts/footer.blade.php`, không phải section riêng của `/blog`.
- Layout chung đã có canonical, OG fallback, RSS, skip link và `:focus-visible`; riêng blog listing chưa set meta description/OG image/page-specific canonical.
- Không truy xuất được visual live từ sandbox: URL bị browser gateway chặn và browser automation thiếu Puppeteer. Audit dưới đây dựa trên source cùng commit; cần QA lại trên staging/production ở 1440/768/375 trước merge.

## Phát hiện ưu tiên

| Ưu tiên | Bằng chứng | Ảnh hưởng | Quyết định |
| --- | --- | --- | --- |
| P0 | `blogs.blade.php:15` chỉ có H1 `Blog`; CSS gốc render 100px uppercase | Không truyền đạt chủ đề/giá trị trong 5 giây | Thay hero nội dung có H1 + lead cụ thể. |
| P0 | `blogs.blade.php:2-3` không có `meta_description`, `og_description`, `og_image` | Listing dùng mô tả/OG site-wide; share không chắc có ảnh | Set title/description/OG theo blog; chỉ dùng OG site đã cấu hình, không tạo asset giả. |
| P1 | `blogs.blade.php:16-24` gộp input, select, nút `Lọc`; category gồm cả category không có bài public | Khó quét chủ đề; mobile dễ chật | Tách nhóm “Tìm bài viết”/“Chủ đề”; controller chỉ trả category có bài published. |
| P1 | `blogs.blade.php:45` ảnh lazy nhưng không `width`/`height`; CSS card crop 3:2 trong khi Admin khuyến nghị upload 16:9 | CLS và crop thiếu nhất quán | Chuẩn hoá card 16:9, khai báo 1200×675; fallback ảnh là decorative, không gán alt tiêu đề. |
| P1 | `main.css:10789` có `transition: all`; card không stretch/khống chế title/description | Grid dễ rung, CTA lệch hàng | Override chỉ trong `.blog-page`, `transition` cụ thể; card flex-column. |
| P2 | `vendor/paginate.blade.php` dùng icon-only link, `...`, active/disabled anchors không state | Keyboard/screen reader thiếu ngữ cảnh | Không nằm trong scope file listing; tạo follow-up a11y riêng, không âm thầm sửa global pagination. |
| P2 | footer newsletter có backend nhưng email chưa có `autocomplete`/`inputmode` | Form mobile chưa tối ưu | Giữ form thật; follow-up nhỏ cho footer, không nhân bản form ở listing. |

## Cấu trúc đề xuất (theo thứ tự)

1. Header hiện hữu.
2. **Hero `.blog-page__hero`**: eyebrow `GÓC CHIA SẺ CHUYÊN MÔN`; H1; lead. Không cần ảnh trang trí hoặc counter.
3. **Discovery panel**: search trước, category filter sau; khi có query/category mới hiện dòng kết quả và nút `Xóa bộ lọc`.
4. **Danh sách bài viết**: heading H2 thay đổi theo state (`Bài viết mới nhất`, `Kết quả cho “…”`, hoặc `Chủ đề: …`); card thật; empty state hiện hữu được giữ.
5. Pagination khi có nhiều trang.
6. **CTA học nhẹ, tĩnh** sau pagination: link `route('courses')`; không card giá, testimonial, urgency hay lead form.
7. Footer newsletter hiện hữu + footer/floating contact hiện hữu.

Không thêm featured/popular/related ở listing: schema không có cờ/số liệu để xác nhận. Trang detail hiện có các bài mới khác; nếu muốn related theo chuyên mục phải làm một phạm vi riêng, với query thật và empty state.

## Copy đã chốt

### Hero

- Eyebrow: `GÓC CHIA SẺ CHUYÊN MÔN`
- **H1:** `Digital Marketing, Performance & Data`
- Lead: `Các bài viết về tư duy, cách triển khai và bài học thực tế trong Digital Marketing, Performance Marketing, dữ liệu và đào tạo.`

Nội dung đủ cụ thể, không hứa hẹn kết quả và không nhồi từ khóa. Có thể giữ “Blog” trong title/meta và breadcrumb, không dùng làm H1 đứng một mình.

### Search/filter + state

- Search visible label: `Tìm bài viết`
- Placeholder: `Tìm theo chủ đề hoặc từ khóa…`
- Category visible label: `Chủ đề`
- Default option: `Tất cả chủ đề`
- Submit: `Tìm bài viết`
- Reset: `Xóa bộ lọc`
- Result label: `Kết quả tìm kiếm cho “{search}”` hoặc `Bài viết về {category name}`. Không hiển thị tổng số nếu không cần cho quyết định đọc.
- Empty state có filter: giữ copy hiện tại; đổi CTA thành `Xem tất cả bài viết`.

### Card

- Date: hiển thị qua `<time datetime>`; không gắn nhãn “Ngày đăng” vì schema chỉ xác nhận `created_at`.
- Category: hiển thị tất cả category đã gán (mỗi chip link lại `/blog?category={slug}`), không dựng tag.
- Title: title thật, 2–3 dòng tối đa visual; title vẫn là accessible name đầy đủ của link.
- Description: chỉ render khi field có dữ liệu; limit **155 ký tự với `…`**; CSS line-clamp 3 để card bền khi dữ liệu dài.
- CTA: `Đọc bài viết` + icon `aria-hidden="true"`. CTA hiện tại đúng intent; tăng prominence bằng vị trí ổn định ở đáy card, không bằng copy giật gân.
- Author: **không hiển thị**. Không có `Author` model/relationship hay author field trên Blog.

### CTA học nhẹ

`Muốn hệ thống hóa kiến thức theo lộ trình? Khám phá các khóa học phù hợp.`
Link: `Xem khóa học` → `route('courses')`.

Đây là một lời mời khám phá, không claim chất lượng/đếm số học viên/đẩy mua.

### Metadata

- `page_title`: `Blog Digital Marketing, Performance & Data | {tên site}`
- `meta_description`, `og_description`: `Góc chia sẻ về Digital Marketing, Performance Marketing, dữ liệu và đào tạo: tư duy, cách triển khai và bài học từ thực tế.`
- `og_title`: cùng page title hoặc `Digital Marketing, Performance & Data | {tên site}`.
- `og_image`: chỉ `@section` từ `data_get($infor, 'og_image')` **nếu có giá trị**. Nếu Setting chưa có, bỏ qua để layout không in ảnh giả; đây là việc content/Setting cần hoàn thành.
- Canonical: page 1 → `/blog`; trang `?page=N` → self-canonical có `page`, nhưng bỏ `search`/`category` nếu chiến lược là tránh index result/filter pages. Cần chốt với SEO owner trước code; an toàn hiện tại là canonical path base, nhưng paginated pages không tự canonical.
- Không cần `ItemList` JSON-LD trong phase này; thiếu mô tả/ảnh chắc chắn trên listing và không tăng giá trị người đọc.

## Quy tắc UX/UI implementation

### `.blog-page` scoped CSS

- Container: 1200–1240px; hero desktop padding 152–168px top (tôn trọng header), section gap 64–80px; 375px dùng gutter 20px, hero padding 112–120px / 48px.
- Hero: H1 `clamp(2.25rem, 5vw, 4.75rem)`, line-height 1.06–1.15; lead max-width 680px, 16–18px, line-height 1.65. Tránh all-caps cho H1 tiếng Việt.
- Discovery: nền surface nhẹ/border 1px; desktop search chiếm phần lớn, category 220–260px, CTA vừa content; mobile một cột, mỗi control/buton cao ≥44px và input 16px để iOS không zoom.
- Cards: desktop 3 cột ở ≥1200px, 2 cột 768–1199px, 1 cột dưới 768px. Card / cột `display:flex`; content `display:flex; flex-direction:column; height:100%`; CTA `margin-top:auto`.
- Image: 16:9, `object-fit:cover`, `width="1200" height="675"`; phải thống nhất lại khuyến nghị Admin. Card thứ nhất có thể `fetchpriority="high"`/eager; card dưới fold `loading="lazy"`. Image không có `image` dùng fallback decorative `alt=""`, không giả vờ mô tả bài.
- Color/focus: dùng token hiện tại (`--theme`, `--header`, `--body`, `--border`), giữ focus 3px có sẵn; normal text ≥4.5:1 ở light/dark. Không thêm gradient/animation trang trí.
- Interaction: card/title/CTA hover chỉ opacity/color/transform 160–220ms, không `transition: all`; tắt transition ở `prefers-reduced-motion`.
- Không overflow ở 375px; category chip wrap, button không phụ thuộc hover.

### A11y/form

- `<form role="search">`, labels **visible** (không chỉ `visually-hidden`), `name="search"`, `type="search"`, `autocomplete="off"`; select giữ label thật.
- Giữ URL state hiện có; `withQueryString()` đã giữ search/category qua pagination. Reset link phải về `route('blogs')`.
- `<section aria-labelledby>` cho hero/discovery/list; `<article>` cho card; heading list là H2, title card H3. Một H1 duy nhất.
- Link ảnh/card có accessible name từ title; icon arrow `aria-hidden="true"`.
- Empty state: heading H2 + hướng dẫn + CTA hiện hữu là đúng hướng; sau filter reset cần là link thật.

## Data/controller boundary

Giữ query/search/filter hiện có. Bổ sung tối thiểu và không migration:

```php
$categories = Category::query()
    ->whereHas('blogs', fn ($query) => $query->where('is_published', 1))
    ->orderBy('name')
    ->get();
```

Điều này chỉ làm filter dễ dùng: không còn category rỗng hoặc chỉ có bài draft. Không thêm `Tag`, `Author`, `featured`, `popular`, `views`, `published_at`, số đếm hay content mẫu.

## File scope cho phase implement

| File | Thay đổi |
| --- | --- |
| `resources/views/blogs.blade.php` | Hero, metadata, discovery/search markup, card semantics/alt/dimensions, heading states, CTA học nhẹ. |
| `app/Http/Controllers/BlogController.php` | Chỉ lọc danh sách category đến category có bài published. |
| `public/site/assets/css/custom.css` | Chỉ thêm selectors dưới `.blog-page`; override CSS theme cũ, không chỉnh global/main.css. |

Không sửa route, schema/migration, Model, pagination global, footer/newsletter hoặc layout trong phase này. `resources/views/blog/index.blade.php` không tồn tại nên không đưa vào scope.

## QA acceptance checklist

- [ ] 5 giây đầu: H1 + lead nói rõ Digital Marketing, Performance, Data và tính triển khai thực tế.
- [ ] Search title/description + category published hoạt động; URL query còn sau sang trang; reset xoá cả hai.
- [ ] Không thêm item/content/ảnh/author/tag/số liệu giả.
- [ ] Ảnh card không CLS, alt đúng, 16:9; thiếu ảnh có alt decorative.
- [ ] 1440: grid 3 cột, title/CTA thẳng hàng; 768: 2 cột; 375: 1 cột, không scroll ngang, controls ≥44px.
- [ ] Light/dark: body/card/category/date/focus readable; keyboard focus nhìn thấy; tab order Header → hero → search → category → result → cards → pagination → CTA → footer.
- [ ] View source có một H1, title/description/OG không rỗng khi Setting OG hợp lệ; canonical theo quyết định query/pagination nêu trên.
- [ ] Footer newsletter submit lưu subscriber/redirect đúng (backend đã có); không tạo form giả ở `/blog`.

## Unresolved questions

1. Setting production có `og_image` thật chưa? Nếu chưa, cần upload/chọn asset thương hiệu thật trước khi yêu cầu OG image cho listing.
2. Xác nhận chiến lược index cho `?search`, `?category`, `?page` (nên `noindex,follow` result/filter; pagination cần self-canonical nếu index) trước khi đổi canonical/layout.
3. Cần QA visual live sau deploy/staging vì sandbox không chụp được `https://dinhtuananh.com/blog`.
