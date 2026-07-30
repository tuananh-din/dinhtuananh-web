---
title: "Phase D - Slug unique, admin delete POST, JSON-LD, throttle login, mail lead"
description: "5 hạng mục build tiếp sau Phase C: 2 hạng mục đụng schema/route cũ cần user xác nhận, 3 hạng mục an toàn"
status: pending
priority: P1
effort: 7h
branch: master
tags: [phase-d, security, seo, admin, migration]
created: 2026-07-23
---

# Phase D — Kế hoạch tổng quan

> **Trạng thái lịch sử: đã triển khai.** Các nhãn `Pending` bên dưới là trạng thái tại thời điểm lập kế hoạch; xem [changelog dự án](../../docs/project-changelog.md#phase-d--2026-07-23-seo-structured-data-bảo-mật-email-lead) để đối chiếu commit thực hiện.

Tiếp nối Phase C. Phase D gồm 3 hạng mục Phase C đã tách ra chờ xác nhận (slug unique, delete POST, JSON-LD)
cộng 2 hạng mục có căn cứ từ code thật (throttle login, email báo lead mới).

## Danh sách hạng mục

| ID | Hạng mục | Ưu tiên | Migration | Đổi route cũ | Cần user xác nhận | Trạng thái | File chi tiết |
|----|----------|---------|-----------|--------------|-------------------|------------|---------------|
| D-01 | Slug `blogs` unique + sửa logic slug admin | P0 | **CÓ** | Không | **CÓ** (schema) | Pending | [phase-d-01](./phase-d-01-blog-slug-unique.md) |
| D-02 | Admin delete GET → POST (6 module) | P0 | Không | **CÓ** (admin) | **CÓ** (route cũ) | Pending | [phase-d-02](./phase-d-02-admin-delete-post.md) |
| D-03 | JSON-LD: Article (blog) + Person (home/about) | P1 | Không | Không | Không | Pending | [phase-d-03](./phase-d-03-jsonld-structured-data.md) |
| D-04 | Throttle route POST login | P1 | Không | Không | Không | Pending | [phase-d-04](./phase-d-04-throttle-login.md) |
| D-05 | Email báo lead mới cho admin | P2 | Không | Không | **CÓ** (SMTP, gửi mail ngoài) | Pending | [phase-d-05](./phase-d-05-lead-email-notification.md) |

## Thứ tự & phụ thuộc

1. **D-04** trước (nhỏ nhất, 1 dòng `routes/web.php`, commit riêng).
2. **D-01** (đụng `app/Http/Controllers/Admin/BlogController.php` + migration) — chỉ chạy sau khi user XÁC NHẬN schema.
3. **D-02** sau D-01 (cùng đụng `Admin/BlogController.php` và `routes/web.php` — tuần tự tránh conflict) — chờ user XÁC NHẬN đổi route.
4. **D-03** độc lập về file (views public + master layout), có thể làm song song D-01/D-02 nhưng khuyến nghị tuần tự cho dễ review.
5. **D-05** cuối — chỉ làm khi user cung cấp SMTP; nếu chưa có thì hoãn (không blocker).

## Hạng mục CẦN USER XÁC NHẬN trước khi code

1. **D-01**: thêm unique index vào `blogs.slug` (đổi schema — AGENTS.md cấm tự ý). Đã kiểm tra DB local: 2 blog, 0 slug trùng → migration an toàn ở local, nhưng PHẢI kiểm tra lại trên production trước khi migrate.
2. **D-02**: đổi 6 route GET delete sang POST (đổi cấu trúc route cũ khu admin). Chỉ ảnh hưởng admin, không ảnh hưởng SEO/public.
3. **D-05**: gửi mail ra ngoài — cần user cung cấp SMTP (hoặc xác nhận dùng log driver để test trước).

## Ngoài scope (ghi nhận, không làm nếu user chưa yêu cầu)

- Cache config/route/view cho production (`php artisan config:cache` ...) — thuộc quy trình deploy, chưa có deploy request.
- Phân trang blog: ĐÃ CÓ (`BlogController::blogs` paginate 12) — không cần làm.

## Cách test chung

Test tay trên http://127.0.0.1:8000 theo hướng dẫn từng phase file. D-01 có bước verify dữ liệu trước migration.
