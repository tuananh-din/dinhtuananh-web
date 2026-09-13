---
date: 2026-09-13
scope: /blog knowledge hub QA
status: blocked
---

# Báo cáo QA — Blog Knowledge Hub

## Tóm tắt

- `git diff --check`: PASS.
- Static review: PASS cho scope Controller/Blade/CSS/test; chưa thấy lỗi cú pháp tĩnh rõ ràng.
- PHPUnit/Artisan: CHƯA CHẠY vì worktree không có `php` trong `PATH` và thiếu thư mục `vendor/`.

## Kiểm tra xác nhận

- Canonical: Blade gửi URL qua `{{ }}`; URL phân trang category sẽ escape `&` thành `&amp;`, đúng expectation test. Search hoặc category không hợp lệ canonical về `/blog`; category hợp lệ giữ `category` và `page`.
- Draft category: `Category::whereHas('blogs', is_published=1)` chỉ đưa category có bài public vào filter. Listing cũng lọc `Blog::where('is_published', 1)`, nên bài nháp/category chỉ có bài nháp không lộ public.
- Query filter có `withQueryString()`; phân trang giữ search/category. Card ảnh có `loading="lazy"`, alt theo title; điều hướng phân trang và kết quả có nhãn ARIA.
- CSS mới scope theo `.blog-page`; block dòng 1787–2163 cân bằng 62 `{` / 62 `}`. Breakpoint 991px và 767px không thấy overflow tĩnh rõ ràng.
- `BlogKnowledgeHubTest` hiện bao phủ metadata, category draft, canonical search/filter/pagination. Expectation phân trang không còn phụ thuộc thứ tự bài viết.

## Blocker chính xác

Không có runtime PHP trong worktree (`command -v php` rỗng) và `vendor/` không tồn tại. Do đó không thể chạy các lệnh bắt buộc:

```bash
php artisan test --filter='BlogKnowledgeHubTest|PublicSeoJsonLdTest|SitemapTest|NewsletterSubscriptionTest'
php artisan test
```

Không chạy `composer install` vì là thay đổi dependency/môi trường ngoài phạm vi QA.

## Khuyến nghị

1. Chạy hai lệnh trên tại môi trường Laravel có PHP 8.1+ và `vendor/`, rồi chỉ merge khi cả targeted và full suite pass.
2. Nếu kết quả runtime khác static review, ưu tiên log PHPUnit; không có lỗi source nào cần sửa từ QA tĩnh hiện tại.

## Câu hỏi còn mở

- Không có; chỉ cần môi trường PHP/vendor để hoàn tất QA runtime.
