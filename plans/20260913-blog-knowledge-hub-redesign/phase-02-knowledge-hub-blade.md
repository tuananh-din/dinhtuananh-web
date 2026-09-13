# Pha 02 — Blade knowledge hub

## Context links

- Parent: [plan.md](./plan.md); contract: [phase 01](./phase-01-public-query-and-seo-contract.md)
- Source: `resources/views/blogs.blade.php`; no card/filter component exists.

## Overview

- Date: 2026-09-13
- Description: thay grid theme thành luồng đọc rõ, nhưng giữ dữ liệu/route hiện có.
- Priority: P1 | Implementation: Complete | Review: Static pass; runtime/visual verification follows Pha 03

## Key insights

- H1 `Blog` không nói nội dung; form dùng label ẩn; card thiếu `article` và result H2.
- Ảnh fallback generic không đại diện bài viết. Newsletter footer là form thật và nằm ngoài listing.

## Requirements

- `@section('body_class', 'blog-page')`; hero copy đúng parent plan, một H1 duy nhất.
- Discovery có `<form role="search">`, label hiển thị, search `type=search`, category select và reset link thật khi có filter.
- Result heading: `Bài viết mới nhất`; search ưu tiên `Kết quả tìm kiếm cho “…”`; category hợp lệ `Bài viết về …`.
- Giữ breadcrumb category, empty states trung thực, pagination template hiện hữu và CTA courses nhẹ sau pagination.

## Architecture

Header → hero định vị → discovery panel → H2 kết quả → collection `article` → pagination → CTA courses → newsletter/footer hiện có.

## Related code files

- Modify: `resources/views/blogs.blade.php`
- Read-only: `resources/views/layouts/footer.blade.php`, `resources/views/vendor/paginate.blade.php`, `app/Models/Blog.php`
- Do not edit: footer/newsletter/pagination global/routes/models.

## Implementation steps

1. Thay hero/form/list structure bằng `section` có `aria-labelledby`; dùng copy chốt và giữ category breadcrumb chỉ khi category hợp lệ.
2. Dùng search/category values thực, reset về `route('blogs')`; giữ `withQueryString()` cho links.
3. Render mỗi record là `<article>`: chỉ khi CMS có ảnh mới render media 16:9 lazy + `decoding="async"`, `width="1200" height="675"` và alt title; không dùng thumbnail generic cho bài thiếu ảnh.
4. Render `<time datetime>` chỉ khi timestamp có; category CMS thành list link; H3 title link; excerpt ưu tiên description, fallback `strip_tags(content)`, giới hạn 155 ký tự, source trống thì không render paragraph.
5. Giữ CTA `Đọc bài viết`; icon chỉ `aria-hidden`; bọc `links()` bằng `nav aria-label="Phân trang bài viết"`. Không sửa partial pagination global.
6. Chỉ khi có danh sách post mới render CTA courses sau pagination; không tạo proof/featured/popular/related hay form mới.

## Todo list

- [x] Hero, discovery, result/empty states đúng copy và hierarchy.
- [x] Card semantic, metadata/alt/excerpt có fallback thật.
- [x] CTA courses nhẹ, conditional; newsletter không đổi.

## Success criteria

- Người đọc biết chủ đề blog trong 5 giây và có thể tìm/lọc/reset bằng keyboard.
- Không author/tag/fake data; không gọi `created_at` là ngày xuất bản.
- Empty/default/filter/card thiếu field không tạo layout rỗng hay link mơ hồ.

## Risk assessment

- Content rich-text có thể rỗng sau strip tags; phải trim trước khi render excerpt.
- Category nhiều tên dài phải wrap, không làm vỡ card; xử lý bằng CSS pha sau.

## Security considerations

- Chỉ output Blade escaped cho title/description/content excerpt/category. Không dùng `{!! !!}` cho fields Blog.

## Next steps

Pha 03 đã thêm CSS `.blog-page` và regression coverage. Cần hoàn tất runtime test và QA responsive thủ công theo Pha 03.
