# Pha 03 — Responsive, QA và phát hành

## Context links

- Parent: [plan.md](./plan.md); UI: [phase 02](./phase-02-knowledge-hub-blade.md)
- Existing tests: `tests/Feature/PublicSeoJsonLdTest.php`, `tests/Feature/SitemapTest.php`.

## Overview

- Date: 2026-09-13
- Description: style cục bộ, regression test public/SEO, visual QA và changelog sau khi pass.
- Priority: P1 | Implementation: Complete | Review: Static pass; runtime PHP/vendor và visual QA pending

## Key insights

- CSS blog đang dựa selector theme chung. Pagination partial có vấn đề a11y riêng, ngoài scope; wrapper `nav` cải thiện landmark mà không tạo side effect.
- Workspace chưa có PHP CLI/vendor; test runtime phải chạy lại trong Laragon theo README.

## Requirements

- Append CSS duy nhất dưới `.blog-page`; không sửa `main.css` hay CSS About/global.
- 1440: 3 cards; 768: 2; 375: 1, gutter 20px, control/button ≥44px, không horizontal overflow.
- Light/dark readable, focus visible, category wrap, motion giảm khi `prefers-reduced-motion`.

## Architecture

`body.blog-page` scope → hero/discovery/grid/card/pagination/CTA responsive. Feature tests seed CMS thật → request `/blog` state → assert markup/SEO/filter contract.

## Related code files

- Modify: `public/site/assets/css/custom.css`, `docs/project-changelog.md` (sau pass)
- Create: `tests/Feature/BlogKnowledgeHubTest.php`
- Verify only: `tests/Feature/SitemapTest.php`, `tests/Feature/NewsletterSubscriptionTest.php`
- No CSS/component/test fixtures or fake public content files.

## Implementation steps

1. Append scoped styles for restrained hero, discovery grid, card flex/equal height, 16:9 media, title/excerpt clamp, card/CTA focus and 1200/768 breakpoints; transition chỉ property cần thiết, không `all`.
2. Tạo `BlogKnowledgeHubTest` để kiểm metadata, inherited global OG image, một H1, valid category canonical, paginated category canonical, base canonical for search/invalid category, public-only category select, filter empty/reset và draft hidden. Giữ `PublicSeoJsonLdTest` dưới 200 dòng.
3. Giữ/assert `SitemapTest` category URL vì đây là lý do canonical category; chạy newsletter test regression, không đổi backend/footer.
4. Chạy `git diff --check`; nếu môi trường đủ, targeted tests rồi `php artisan test`. Nếu không đủ, ghi rõ gate chưa chạy, không coi là pass.
5. QA browser 1440/768/375: no overflow, form/category card lâu/thiếu ảnh, light/dark, Tab/focus/Enter, reduced motion, canonical/meta source. Khi mọi gate pass mới ghi changelog ngắn.

## Todo list

- [x] CSS only under `.blog-page`.
- [x] Public + SEO feature coverage đủ state, gồm query không scalar và search `0`.
- [x] Final re-review tĩnh không còn P0/P1/P2; `git diff --check` pass và limitation runtime PHP/vendor được ghi rõ.
- [x] Changelog closeout ghi implementation chưa commit, scope và gate còn lại.
- [ ] Chạy targeted/full PHPUnit trong môi trường Laravel có PHP CLI + `vendor/`.
- [ ] QA browser sau deploy/staging ở 1440px/768px/375px; chưa xác nhận deploy trong plan này.

## Success criteria

- Implementation/static review: grid/card/action có CSS 3/2/1 scoped, ảnh 16:9, title/excerpt clamp và CTA flex; contract search/category/pagination/canonical/metadata được feature test mô tả.
- `git diff --check` sạch; P1 query array và vùng chạm reset đã được sửa trong source cuối.
- Gate runtime và visual chưa pass vì workspace thiếu PHP CLI/`vendor/` và sandbox không render live ổn định; không bỏ qua, phải chạy theo checklist bên dưới.

## Risk assessment

- Test string canonical phụ thuộc thứ tự query: use `route()` kỳ vọng đúng thứ tự controller/view tạo.
- Production cần visual smoke test vì asset/theme cũ có thể override selector; không mở rộng scope trước khi có bằng chứng.

## Security considerations

- Không mở endpoint, POST hay subscriber flow. Accessibility changes không vô hiệu hóa CSRF/throttle.

## Next steps

1. Tại Laravel có PHP 8.1+ và `vendor/`, chạy targeted `BlogKnowledgeHubTest`, `PublicSeoJsonLdTest`, `SitemapTest`, `NewsletterSubscriptionTest`, sau đó `php artisan test`.
2. Sau deploy/staging, kiểm tra 1440px/768px/375px: không overflow; reset ≥44px; card thiếu ảnh/category-title dài; light/dark; Tab/focus/Enter; reduced motion; source title/meta/OG/canonical.
3. Slug legacy live dạng `httpsdinhtuananhcom...` chỉ làm sạch cùng 301 redirect từ URL cũ trong follow-up SEO/route, không sửa trực tiếp trong scope này.
