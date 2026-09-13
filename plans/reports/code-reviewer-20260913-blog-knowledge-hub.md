---
date: 2026-09-13
scope: Pending /blog knowledge hub changes
reviewer: code-reviewer
status: changes_requested
---

# Review đối kháng — Blog Knowledge Hub

## Phạm vi và bằng chứng

- Diff đã đọc: `app/Http/Controllers/BlogController.php`, `resources/views/blogs.blade.php`, `public/site/assets/css/custom.css`, và test mới `tests/Feature/BlogKnowledgeHubTest.php`.
- Context đã đối chiếu: `layouts/master`, `vendor/pagination`, `Blog`, `Category`, route `/blog`, các test SEO/sitemap/newsletter và 3 phase plan.
- `git diff --check`: sạch.
- Runtime gate chưa xác minh: worktree không có `php` trong `PATH` và thiếu `vendor/`; chưa thể chạy PHPUnit hay render browser.

## Kết luận

**Không chấp nhận để merge ở trạng thái hiện tại.** Có 2 P1 cần sửa. Không có P0.

## Giai đoạn 1 — Đối chiếu plan/spec

| Hạng mục | Verdict | Bằng chứng |
| --- | --- | --- |
| Query category public-only, giữ search/pagination/route | PASS có điều kiện | `BlogController.php:15-28` dùng `whereHas(...is_published=1)`, eager-load, `paginate(12)`, `withQueryString()`; rủi ro query array ở P1-1. |
| Canonical/title/meta/OG contract | PASS | `blogs.blade.php:4-31` tạo title/description chốt; search và category invalid về root, category/page valid tự canonical; không override OG image fallback master. |
| Hero, discovery, card semantics, empty state, CTA | PASS | Một H1 (`:45`), form search/label (`:56-74`), `article/time/H3` (`:103-126`), pagination landmark và CTA có điều kiện (`:131-139`). Không thấy dữ liệu giả. |
| Card image/excerpt fallback | PASS | Thumbnail chỉ có khi `image` thật, lazy/alt/dimension; excerpt description rồi `strip_tags(content)`, source trống không render (`:100-124`). |
| Responsive/a11y control ≥44px theo Pha 03 | FAIL | Reset filter bị hạ còn `min-height: 30px` ở `custom.css:2114-2118`, áp dụng cả 768px và 375px. Xem P1-2. |
| CSS scope/chỉ sửa file được phép | PASS | Khối mới dùng namespace `.blog-page*`; không đổi route/layout/model/schema/pagination global/footer. |
| Test/changelog/release gate Pha 03 | CHƯA ĐẠT GATE | Test mới có mặt, nhưng không chạy được vì thiếu PHP/vendor; changelog đúng ra chỉ cập nhật sau khi mọi gate pass. |

## Giai đoạn 2 — Chất lượng code

| Hạng mục | Verdict | Bằng chứng |
| --- | --- | --- |
| SQL/XSS | PASS cho input scalar | Eloquent binding dùng `where/like`, không raw SQL; các giá trị dynamic trong Blade dùng `{{ }}`; excerpt strip HTML rồi escape. |
| N+1/null/empty | PASS | Categories eager-load; category/created_at/image/excerpt đều guard trước render; out-of-range pagination canonical đã được test. |
| Query state | FAIL | Cast trực tiếp query value không chịu được input dạng array. Xem P1-1. |
| Test coverage | P2 | Chưa có assertion cho `category[]`/`search[]`, tap target reset ở tablet/mobile, và card không ảnh không lộ fallback generic. Xem P2-1. |

## Giai đoạn 3 — Red-team findings

### P1-1 — Query array có thể làm lỗi public listing

**Verdict: ACCEPT — phải sửa trước merge.**

- Evidence: `app/Http/Controllers/BlogController.php:14-15` gọi `trim((string) $request->query('search'))` và `trim((string) $request->query('category'))`.
- Attack/state: một crawler hoặc URL thủ công như `/blog?category[]=data` (tương tự `search[]=data`) đưa array vào cast string. PHP phát cảnh báo `Array to string conversion`; theo handler Laravel cảnh báo này có thể thành `ErrorException`/HTTP 500 thay vì một empty state an toàn.
- Impact: public URL không hợp lệ có thể làm endpoint lỗi, làm QA query state và crawler robustness không đạt.
- Hướng sửa tối thiểu: chỉ trim khi input là string, còn scalar/array không hợp lệ chuẩn hóa về chuỗi rỗng. Giữ nguyên query title/description và route/schema.

### P1-2 — Link “Xóa bộ lọc” không đạt target chạm tối thiểu ở tablet/mobile

**Verdict: ACCEPT — phải sửa trước merge.**

- Evidence: `public/site/assets/css/custom.css:1911-1919` đặt 48px ban đầu, nhưng media `max-width: 991px` tại `:2114-2118` ghi đè `min-height: 30px; padding: 0`. Media mobile `:2143-2145` không khôi phục kích thước.
- Impact: tại 768px và 375px, control reset chỉ tối thiểu 30px, trái success criteria Pha 03 “control/button ≥44px”; giảm khả năng thao tác cảm ứng/keyboard focus cho action khôi phục listing.
- Hướng sửa tối thiểu: giữ reset tối thiểu 44px ở mọi breakpoint (và layout/align phù hợp), không chỉ dùng `min-height:30px` để ép form thấp.

### P2-1 — Test regression chưa khóa các failure mode mới

**Verdict: ACCEPT — nên bổ sung cùng lúc sửa P1.**

- Evidence: `tests/Feature/BlogKnowledgeHubTest.php` có metadata/category/canonical/empty/out-of-range tốt, nhưng chưa gửi query array, chưa kiểm card thiếu ảnh để đảm bảo không render `thumb-16.jpg`, và không có kiểm tra marker CSS/HTML cho reset target responsive.
- Impact: hai regression P1 hiện không bị suite phát hiện; fallback image của acceptance criteria có thể đổi ngược mà test không báo.
- Hướng sửa tối thiểu: thêm test HTTP cho `category[]`/`search[]` trả listing an toàn; test record không ảnh không chứa fallback generic. Target touch nên kiểm bằng visual/mobile QA, không mock CSS vào PHPUnit.

## Các mục red-team đã thử và không tạo finding

- SQL injection qua search/category scalar: Query Builder bind tham số; không có raw expression.
- XSS qua search, title, category, description/content: Blade escaping còn nguyên; `strip_tags()` chỉ làm source excerpt sạch hơn trước khi escape.
- Draft/soft-deleted blog/category: listing và dropdown cùng ràng buộc `is_published`; quan hệ Eloquent mặc định loại soft delete.
- Empty/thiếu metadata: no-image không gọi fallback thumbnail; `created_at`, categories, excerpt đều có guard.
- Pagination/canonical: search bỏ toàn bộ query; category valid + page hợp lệ giữ URL tự canonical; page out-of-range không sinh canonical page lạ.
- OG fallback: listing không set `og_image`; `layouts.master` tiếp tục lấy `Setting::og_image`.

## Gate còn lại sau khi sửa

1. `git diff --check`.
2. Ở môi trường có PHP 8.1+ và `vendor/`: chạy targeted `BlogKnowledgeHubTest`, `PublicSeoJsonLdTest`, `SitemapTest`, `NewsletterSubscriptionTest`, sau đó `php artisan test`.
3. Visual smoke 1440/768/375: reset ≥44px, không overflow, no-image card, long category/title, light/dark, Tab/focus và reduced motion.

## Câu hỏi chưa giải quyết

Không có; blocker là hai P1 và gate runtime/visual chưa có môi trường.

**Status:** DONE_WITH_CONCERNS
**Summary:** Review ba giai đoạn phát hiện 2 P1: query array có thể gây lỗi public và reset filter dưới 44px ở tablet/mobile. Không có P0; runtime/browser QA chưa chạy vì worktree thiếu PHP/vendor.
**Concerns/Blockers:** Không merge trước khi sửa P1 và chạy lại targeted/full test cùng smoke test responsive.
