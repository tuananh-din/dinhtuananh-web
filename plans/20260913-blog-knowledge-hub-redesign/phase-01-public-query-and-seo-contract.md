# Pha 01 — Query public và SEO contract

## Context links

- Parent: [plan.md](./plan.md)
- Evidence: [scout](../reports/scout-20260913-blog-page-codebase.md), [UX/UI brief](../reports/uiux-20260913-blog-knowledge-hub.md)
- Dependencies: sitemap đang công bố category URL; `layouts.master` đã render canonical/OG fallback.

## Overview

- Date: 2026-09-13
- Description: chỉ tinh chỉnh dữ liệu cho discovery và chốt URL/metadata không mâu thuẫn sitemap.
- Priority: P1 | Implementation: Complete | Review: Static pass; runtime verification follows Pha 03

## Key insights

- `blogs()` đã lọc published, eager-load categories, paginate 12 và `withQueryString()`.
- Dropdown hiện gồm category rỗng/draft-only. Blog không có author/tag/published_at/featured/popular.
- Master nhận `@section('canonical')`, `meta_description`, `og_title`, `og_description`; ảnh OG tự fallback từ `Setting::og_image`.

## Requirements

- Chỉ trả dropdown category có Blog published; order theo `name`.
- Không đổi search title/description, pagination, route hoặc sitemap.
- Canonical hợp lệ: `/blog`; `/blog?page=N`; `/blog?category=slug`; `/blog?category=slug&page=N`.
- Nếu `search` không rỗng, canonical luôn `/blog`; category không hợp lệ cũng `/blog`.

## Architecture

`GET /blog` → `BlogController@blogs` lấy posts public + categories public → `blogs.blade.php` suy ra selected category hợp lệ và canonical → `layouts.master` in canonical/metadata/OG image fallback.

## Related code files

- Modify: `app/Http/Controllers/BlogController.php`, `resources/views/blogs.blade.php`
- Read-only: `resources/views/layouts/master.blade.php`, `resources/views/sitemap.blade.php`
- No routes/schema/migrations/models.

## Implementation steps

1. Thay query `$categories` bằng `whereHas('blogs', is_published=1)->orderBy('name')`; giữ collection truyền view và query post hiện hữu.
2. Trong Blade, resolve category từ collection public; chỉ category này được dùng cho breadcrumb, heading và canonical category.
3. Build canonical bằng `route('blogs', parameters)`; không nối URL thủ công. `search` ưu tiên canonical base; valid category/page giữ self; invalid category canonical base.
4. Set title/meta/OG text của listing bằng copy đã chốt; không set `og_image` để master dùng Setting khi có.

## Todo list

- [x] Category menu public-only.
- [x] Canonical state contract được implement đúng.
- [x] Metadata non-empty, escaped by current master.

## Success criteria

- Sitemap category URL nhận canonical cùng category query; search không sinh landing indexable.
- Không có query/model/schema mới ngoài `whereHas` category public.

## Risk assessment

- Direct URL category đã bị unpublish sẽ trả empty state nhưng canonical root; không 404/đổi hành vi route.
- Master `og:url` dùng path hiện tại; không mở scope layout chỉ để đổi thẻ này.

## Security considerations

- Query values vẫn được Blade escape; `route()` bảo toàn encoding. Không thêm raw HTML/SQL/raw query.

## Next steps

Pha 02 và 03 đã được triển khai. Runtime test URL contract vẫn cần chạy trong môi trường có PHP CLI và `vendor/`.
