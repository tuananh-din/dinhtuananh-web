# Pha 01 — Contract dữ liệu public và SEO

## Context links

- [HomeController@about](../../app/Http/Controllers/HomeController.php#L69-L76)
- [Route `/about`](../../routes/web.php#L42)
- [Layout metadata](../../resources/views/layouts/master.blade.php#L32-L57)
- [Person JSON-LD](../../resources/views/partials/jsonld-person.blade.php)

## Overview

- **Ngày:** 2026-09-13
- **Ưu tiên:** P1
- **Trạng thái:** Hoàn tất
- **Mục đích:** Nạp đúng dữ liệu public để view có thể chứng minh nội dung đào tạo mà không sửa contract khác.

## Key insights

- `about()` hiện trả `about`, `image`, tối đa ba Course active, hai CaseStudy published và hai Blog published; route và Person JSON-LD giữ nguyên.
- Course có `is_active`; CaseStudy/Blog có `is_published`. Service, Testimonial và phương pháp giảng dạy không có contract/approval đủ để public ở lần này, nên không được query hay render.
- `about` có sẵn `title_seo`, `desc_seo`, `avatar`, contact và social; layout hỗ trợ page-level title, meta/OG/canonical/image.

## Requirements

- Giữ `About::first() ?? new About()` và ảnh `Image(type=1)`.
- Thêm tối đa ba Course active (`sort_order`, rồi `id` giảm dần); hai CaseStudy published mới nhất và hai Blog published mới nhất.
- Không nạp draft/inactive, không fallback CaseStudy sang `Image(type=0)`, không thêm migration, route, seed hoặc model scope.
- Đặt metadata `/about` từ `about.title_seo/desc_seo` khi có; fallback không rỗng sang tên/mô tả About. Mô tả strip HTML và giới hạn 160 ký tự; layout Blade escape title/meta/OG/canonical. Canonical luôn `route('about')`; OG image chỉ dùng ảnh profile thật nếu có, nếu không layout fallback Setting.

## Architecture

`GET /about` → `HomeController@about()` → Course/CaseStudy/Blog đã lọc → `about.blade.php` → `layouts.master` metadata đã escape + Person JSON-LD giữ nguyên. Không có POST, không thay đổi lead/newsletter/contact.

## Related code files

- **Sửa:** `app/Http/Controllers/HomeController.php`
- **Sửa ở pha 02:** `resources/views/about.blade.php`
- **Sửa:** `resources/views/layouts/master.blade.php` để escape phần metadata động tại ranh giới layout
- **Không sửa:** `routes/web.php`, models, migrations, `partials/jsonld-person.blade.php`

## Implementation steps

1. Đã thêm ba query public vào duy nhất `about()` với order/limit nêu trên: `courses`, `caseStudies`, `blogs`.
2. Đã truyền các collection tối thiểu sang view; không thay đổi query của trang chủ, portfolio hay contact.
3. Đã khai báo metadata có fallback ở view và escape giá trị động tại layout; giữ nguyên `@push('structured_data')` include Person.
4. Đã đối chiếu link course/case/blog dùng route hiện có và draft/inactive không thể xuất hiện.

## Todo list

- [x] Query Course active đúng thứ tự và giới hạn.
- [x] Query CaseStudy/Blog published, không dùng legacy image fallback.
- [x] Không query Service/Testimonial chưa có xác nhận public.
- [x] Pass collections vào view và không đổi route/schema.
- [x] Chốt fallback metadata không rỗng, strip HTML mô tả và escape output động.

## Success criteria

- `/about` trả 200 khi database rỗng lẫn khi đủ record.
- Không có title/content từ Course inactive hoặc CaseStudy/Blog draft trong response.
- Canonical `/about`, metadata escaped có fallback và Person JSON-LD cùng render mà không thay JSON-LD schema hiện hữu.

## Risk assessment

- Trộn fallback legacy Image vào CaseStudy sẽ biến ảnh không có ngữ cảnh thành social proof; tránh hoàn toàn.
- `about_me` là HTML rich text, không dùng nó trực tiếp trong thẻ meta/OG description.

## Security considerations

- Không thêm raw metadata; title/meta/OG/canonical động đi qua Blade escaping.
- Giữ filter published/active để không lộ nội dung admin nháp.

## Next steps

Pha 02 đã dùng collections đã lọc để xây section course/evidence conditional.
