---
title: "Phase C - Hoan thien SEO ky thuat, chong spam va don dep frontend"
description: "6 hang muc nho, an toan: throttle lead form, sitemap/canonical, trang 404, tach not inline CSS, chuan hoa anh blog, giam query trung"
status: pending
priority: P1
effort: 6h
branch: master
tags: [phase-c, seo, security, cleanup]
created: 2026-07-23
---

# Phase C — Kế hoạch tổng quan

> **Trạng thái lịch sử: đã triển khai.** Các nhãn `Pending` bên dưới là trạng thái tại thời điểm lập kế hoạch; xem [changelog dự án](../../docs/project-changelog.md#phase-c--2026-07-23-seo-on-page--cleanup) để đối chiếu commit thực hiện.

Tiếp nối Phase A (asset, menu, jquery, meta OG) và Phase B (card blog, blog detail, custom.css, honeypot).
Phase C tập trung: **chống spam mạnh hơn, SEO kỹ thuật, hoàn thiện việc dọn dẹp đã bắt đầu ở Phase B**.
Toàn bộ 6 hạng mục **KHÔNG có migration**, không đổi schema, không đổi route cũ — đúng AGENTS.md.

## Danh sách hạng mục

| ID | Hạng mục | Ưu tiên | Migration | Trạng thái | File chi tiết |
|----|----------|---------|-----------|------------|---------------|
| C-01 | Throttle (rate limit) cho lead form | P0 | Không | Pending | [phase-c-01](./phase-c-01-throttle-lead-form.md) |
| C-02 | SEO kỹ thuật: canonical + sitemap.xml + robots.txt | P0 | Không | Pending | [phase-c-02](./phase-c-02-seo-canonical-sitemap.md) |
| C-03 | Trang 404 tùy brand | P1 | Không | Pending | [phase-c-03](./phase-c-03-custom-404-page.md) |
| C-04 | Tách nốt inline CSS còn lại ra custom.css | P1 | Không | Pending | [phase-c-04](./phase-c-04-finish-inline-css-extraction.md) |
| C-05 | Chuẩn hóa ảnh blog: asset() + fallback | P1 | Không | Pending | [phase-c-05](./phase-c-05-blog-image-asset-fallback.md) |
| C-06 | Giảm query trùng của view composer | P2 | Không | Pending | [phase-c-06](./phase-c-06-reduce-view-composer-queries.md) |

## Thứ tự & phụ thuộc

- C-01, C-02, C-03 độc lập, làm trước (P0/P1, giá trị cao nhất).
- C-04 và C-05 cùng đụng `blogs.blade.php`, `blog_detail.blade.php`, `home.blade.php` → **làm tuần tự C-04 trước rồi C-05** (tránh conflict), mỗi cái 1 commit riêng.
- C-06 độc lập, làm cuối.

## Hạng mục CẦN USER XÁC NHẬN (không nằm trong Phase C mặc định)

1. **Unique slug cho bảng `blogs`** — cột `slug` hiện KHÔNG unique (migration `2026_01_05_084900`). Trùng slug → firstOrFail lấy bài sai. Cần migration → phải được xác nhận trước.
2. **Admin delete dùng GET** (`/admin/blog/delete/{id}`...) — rủi ro CSRF, nhưng thuộc khu admin, đổi sang POST là đổi cấu trúc route cũ → cần xác nhận.
3. **JSON-LD structured data (Article/Person)** — SEO nâng cao, chưa cấp thiết (YAGNI), chỉ làm nếu user muốn đẩy SEO mạnh.

## Cách test chung

Chạy local Laragon, test tay theo hướng dẫn trong từng phase file. Không có test tự động cho các hạng mục view (project chưa có test suite cho frontend).
