---
title: "Blog knowledge hub"
description: "Làm rõ định vị, discovery và SEO của /blog bằng dữ liệu CMS hiện có."
status: implementation_complete
priority: P1
effort: 1d
branch: master
tags: [blog, ux, seo, accessibility, blade]
created: 2026-09-13
---

# Kế hoạch — Blog knowledge hub

## Mục tiêu

Biến `/blog` thành nơi khám phá kiến thức Digital Marketing đáng tin: hiểu chủ đề trong 5 giây, tìm/lọc bài dễ hơn, đọc tiếp tự nhiên và có lời mời nhẹ sang khóa học. Không biến listing thành trang bán hàng.

## Quyết định đã chốt

- View thật là `resources/views/blogs.blade.php`; không có `resources/views/blog/index.blade.php` hay component card/filter riêng.
- Chỉ sửa `BlogController`, view thật, CSS append dưới `.blog-page`, test public/SEO hiện hữu và changelog sau khi nghiệm thu. Không sửa route, layout, schema, migration, model, sitemap, footer hay pagination global.
- Newsletter footer giữ nguyên: Subscriber + Brevo backend đã có. Không tạo newsletter form/author/tag/featured/popular/related, bài viết, ảnh, số liệu hoặc claim giả.
- Category dropdown chỉ hiển thị category có ít nhất một Blog `is_published=1`; search vẫn chỉ title/description để tránh mở rộng truy vấn `content` ngoài yêu cầu.
- SEO canonical: category hợp lệ giữ query category vì sitemap đã công bố URL đó; search (kể cả có category/page) canonical về `/blog`. Page không search canonical self, giữ `page` và category hợp lệ. Category không hợp lệ canonical `/blog`.
- Metadata/OG của listing do Blade set title + description; OG image tiếp tục fallback Setting hiện có từ `layouts.master`. Không thêm asset OG hay JSON-LD mới.

## Trạng thái closeout

- Phần triển khai của cả ba phase đã hoàn tất trong worktree: query public, canonical/metadata, Blade knowledge hub, CSS scoped và feature coverage.
- Hai P1 từ review đã được xử lý trước closeout: query `search[]`/`category[]` được chuẩn hóa về rỗng; reset filter giữ vùng chạm tối thiểu 48px ở tablet/mobile.
- Final re-review tĩnh không còn P0/P1/P2; `git diff --check` cũng pass. Runtime PHPUnit/Artisan chưa chạy vì worktree thiếu PHP CLI và `vendor/`; visual QA live cũng chưa được xác nhận. Vì vậy trạng thái này không xác nhận merge, deploy hay production.

## Phụ thuộc và không xung đột

- `plans/260723-1056-phase-c`, `260723-1315-phase-d`, `260723-1501-phase-e` là kế hoạch lịch sử; changelog ghi các nền tảng canonical/sitemap/OG/blog đã triển khai. Scope này không đụng các hạng mục pending lịch sử.
- `20260913-about-instructor-profile-redesign` đã hoàn tất; chỉ cùng file `custom.css`, nên append khối `.blog-page` mới, không thay CSS About.
- Căn cứ: [scout codebase](../reports/scout-20260913-blog-page-codebase.md) và [UX/UI brief](../reports/uiux-20260913-blog-knowledge-hub.md).

## Pha thực hiện

| Pha | Status | Deliverable | File |
|---|---|---|---|
| 01 | Implementation complete | Query category public và contract canonical/SEO | [phase-01](./phase-01-public-query-and-seo-contract.md) |
| 02 | Implementation complete | Blade knowledge hub, nội dung, semantics và state | [phase-02](./phase-02-knowledge-hub-blade.md) |
| 03 | Implementation complete; runtime/visual pending | CSS scoped, feature/SEO test, QA và changelog | [phase-03](./phase-03-responsive-qa-and-release.md) |

## Copy đã chốt

- Eyebrow: `GÓC CHIA SẺ CHUYÊN MÔN`
- H1: `Digital Marketing, Performance & Data`
- Lead: `Các bài viết về tư duy, cách triển khai và bài học thực tế trong Digital Marketing, Performance Marketing, dữ liệu và đào tạo.`
- Search/Filter: `Tìm bài viết`; `Tìm theo chủ đề hoặc từ khóa…`; `Chủ đề`; `Tất cả chủ đề`; `Xóa bộ lọc`.
- CTA card: `Đọc bài viết`. CTA sau listing: `Muốn hệ thống hóa kiến thức theo lộ trình? Khám phá các khóa học phù hợp.` / `Xem khóa học`.
- Title: `Blog Digital Marketing, Performance & Data | {tên site}`. Meta/OG description: `Góc chia sẻ về Digital Marketing, Performance Marketing, dữ liệu và đào tạo: tư duy, cách triển khai và bài học từ thực tế.`

## Guardrails nghiệm thu

- Một H1; H2 cho khu vực kết quả; H3 trong card. Dùng `section`, `article`, `time`, label hiển thị và landmark tìm kiếm/phân trang.
- Card chỉ render metadata CMS thật: category, `created_at` (không gọi là ngày xuất bản), title, ảnh thật và description hoặc excerpt đã strip HTML từ `content`; thiếu ảnh thì không dựng thumbnail thay thế, thiếu cả mô tả/content thì bỏ excerpt. Không hiện author/tag.
- Empty mặc định dẫn tới courses; empty có filter có link `/blog`. Reset bỏ cả search/category. `withQueryString()` tiếp tục giữ state khi phân trang.
- QA 1440/768/375, light/dark, keyboard/focus/reduced-motion, text dài/thiếu ảnh/không có kết quả. Chạy `git diff --check`, targeted tests rồi `php artisan test` trong Laragon khi môi trường workspace thiếu PHP/vendor.

## Gate còn lại

1. Trong môi trường có PHP 8.1+ và `vendor/`, chạy targeted `BlogKnowledgeHubTest`, `PublicSeoJsonLdTest`, `SitemapTest`, `NewsletterSubscriptionTest`, sau đó chạy `php artisan test`.
2. Không xác nhận deploy trong plan này. Sau deploy/staging, kiểm tra tay 1440px/768px/375px và source metadata/canonical theo checklist Pha 03.
3. Bài live có slug legacy dạng `httpsdinhtuananhcom...` chỉ được làm sạch trong follow-up có 301 redirect từ URL cũ; không coi đây là chỉnh CMS đơn lẻ.
