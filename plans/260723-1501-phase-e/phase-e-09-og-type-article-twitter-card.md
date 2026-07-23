# E-09 — `og:type=article` cho blog detail + thẻ `twitter:card`

## Overview
- Priority: P2 | Status: Pending | Migration: KHÔNG | Đổi route: KHÔNG

## Key Insights (đã verify)
- `resources/views/layouts/master.blade.php:29`: `<meta property="og:type" content="website">` hardcode — trang blog detail cũng bị `website` (chuẩn OG là `article` cho bài viết).
- `blog_detail.blade.php:10-15` đã có đủ section `og_title/og_description/og_image` (Phase A/B) — chỉ thiếu og:type + twitter card.
- Chưa có thẻ `twitter:card` nào trong master → share lên X/Telegram/Discord dùng fallback kém đẹp.

## Requirements
- Blog detail: `og:type=article`; các trang khác giữ `website`.
- Toàn site: `twitter:card=summary_large_image` + `twitter:title/description/image` tái dùng giá trị OG (không thêm section mới — DRY).

## Related Code Files
- Sửa: `resources/views/layouts/master.blade.php` (og:type thành `@yield('og_type', 'website')` + 4 thẻ twitter dùng cùng `@yield` với OG),
  `resources/views/blog_detail.blade.php` (thêm `@section('og_type', 'article')`).
- Tạo/Xoá: KHÔNG.

## Implementation Steps
1. master.blade.php dòng 29: `<meta property="og:type" content="@yield('og_type', 'website')">`.
2. Thêm ngay dưới block OG:
   `twitter:card=summary_large_image`, `twitter:title=@yield('og_title', $siteName)`, `twitter:description=@yield('og_description', $seoDescription)`, `twitter:image=@yield('og_image', $defaultOgImageUrl)` (dùng `name=` attribute, không phải `property=`).
3. blog_detail.blade.php: thêm 1 dòng `@section('og_type', 'article')` cạnh các section OG hiện có (dòng 12-15).

## Todo
- [ ] Sửa master.blade.php
- [ ] Sửa blog_detail.blade.php
- [ ] Test tay theo Success Criteria
- [ ] Commit: `feat: og type article cho blog va twitter card toan site`

## Success Criteria (test tay)
1. View-source http://127.0.0.1:8000/{slug}.html → `og:type=article`, đủ 4 thẻ twitter, twitter:image = og:image.
2. View-source `/`, `/about`, `/courses` → `og:type=website`, twitter card dùng fallback site.
3. (Sau deploy) kiểm tra bằng opengraph.xyz hoặc Facebook Sharing Debugger.

## Risk Assessment
- Rất thấp: thêm meta tag, không đụng logic. Rollback: revert 2 file.

## Security Considerations
- Giá trị đã escape qua Blade section (giữ nguyên cơ chế hiện tại của og_title/og_description).

## Next Steps
- Độc lập. Lưu ý: chỉ mục này đụng `layouts/master.blade.php` trong Phase E — không conflict.
