---
title: "Phase E - Fix ton dong audit + social columns + nang cap lead admin"
description: "9 hang muc: 4 fix nho an toan (A10-A13), 1 migration social columns sua bug profile save, jsonld sameAs, don dead code Social, loc lead admin, og:type article"
status: done
priority: P1
effort: 4h
branch: master
tags: [phase-e, bugfix, seo, admin, migration]
created: 2026-07-23
---

# Phase E — Kế hoạch tổng quan

Tiếp nối Phase C+D+fix audit A1-A9. Phase E gồm: các mục LOW còn tồn (A10-A13), 1 **bug P0 mới phát hiện**
(lưu admin profile lỗi 500 vì bảng `about` thiếu 4 cột social mà form + controller đang gửi), và 2 hạng mục
build-out có căn cứ từ code thật. Mọi mục đụng schema / xoá module đều đánh dấu **CẦN USER DUYỆT**.

## Danh sách hạng mục

| ID | Hạng mục | Ưu tiên | Migration | Route/Xoá module | Cần duyệt | Trạng thái | File |
|----|----------|---------|-----------|------------------|-----------|------------|------|
| E-01 | `RouteServiceProvider::HOME` `/home` → `/admin` (A10) | P1 | Không | Không | Không | **Done (chờ QC mắt)** | [phase-e-01](./phase-e-01-route-home-redirect.md) |
| E-02 | JSON-LD thêm `JSON_HEX_TAG` chống thoát `</script>` | P1 | Không | Không | Không | **Done (chờ QC mắt)** | [phase-e-02](./phase-e-02-jsonld-hex-tag.md) |
| E-03 | CKEditor uploadUrl: bỏ `?_token=`, dùng header X-CSRF-TOKEN (A12) | P1 | Không | Không | Không | **Done (chờ QC mắt)** | [phase-e-03](./phase-e-03-ckeditor-csrf-header.md) |
| E-04 | Dọn login.blade: bỏ footer template, title brand, thêm remember (A13) | P1 | Không | Không | Không | **Done (chờ QC mắt)** | [phase-e-04](./phase-e-04-login-page-cleanup-remember.md) |
| E-05 | Migration thêm cột `facebook/instagram/linkedin/x` vào `about` — sửa bug 500 lưu profile | **P0** | **CÓ** | Không | **CÓ** (schema) | **Done (migrate chạy, verify)** | [phase-e-05](./phase-e-05-about-social-columns-migration.md) |
| E-06 | JSON-LD Person thêm `sameAs` từ 4 cột social | P2 | Không | Không | Không | **Done (test sameAs OK)** | [phase-e-06](./phase-e-06-jsonld-sameas.md) |
| E-07 | Xoá dead code `SocialController` + model `Social` (GIỮ bảng `socials`) (A11) | P2 | Không | **CÓ** (xoá file) | **CÓ** (xoá module) | **Done (route:list OK)** | [phase-e-07](./phase-e-07-remove-social-dead-code.md) |
| E-08 | Admin lead: lọc theo status + hiện tên khoá học + đếm theo trạng thái | P2 | Không | Không | Không | **Done (chờ QC mắt admin)** | [phase-e-08](./phase-e-08-admin-lead-filter-course-name.md) |
| E-09 | `og:type=article` cho blog detail + `twitter:card` | P2 | Không | Không | Không | **Done (verify HTML)** | [phase-e-09](./phase-e-09-og-type-article-twitter-card.md) |

## Thứ tự & phụ thuộc (mỗi mục 1 commit)

1. **Nhóm an toàn, độc lập, làm ngay:** E-01 → E-02 → E-03 → E-04 (không cần duyệt).
2. **E-05** chỉ chạy sau khi user DUYỆT schema (migration 4 cột nullable, rollback = drop 4 cột, không mất data cũ).
3. **E-06** phụ thuộc E-05 (cần cột tồn tại + có data).
4. **E-08**, **E-09** độc lập, làm sau nhóm 1 (E-09 đụng `layouts/master.blade.php` — không trùng file với mục nào).
5. **E-07** làm CUỐI, chỉ khi user duyệt xoá 2 file dead code (không drop bảng).

## Hạng mục CẦN USER DUYỆT

1. **E-05** — đổi schema `about` (AGENTS.md cấm tự ý). Lý do bắt buộc: form profile + `AboutController::updateProfile` (dòng 40-42) đã gửi 4 field này → `updateOrCreate` lỗi SQL "Unknown column". Không migration thì phải gỡ tính năng social khỏi form/controller (phương án B trong phase file).
2. **E-07** — xoá module (controller rỗng + model không dùng, 0 route, bảng `socials` 0 dòng). AGENTS.md: xoá module phải hỏi. Đề xuất chỉ xoá 2 file PHP, KHÔNG drop bảng.

## Ghi chú — KHÔNG làm trong Phase E (YAGNI / chờ user)

- **SMTP thật cho D-05**: code đã xong (`Mail::to(config('mail.lead_notify'))`, fail thì log). Chỉ cần user cấp SMTP → sửa `.env` (MAIL_*, LEAD_NOTIFY_EMAIL). Việc cấu hình, không phải code.
- **Prod deploy checklist**: `APP_URL=https://dinhtuananh.com`, `config:cache`. Thuộc deploy, chưa có yêu cầu.
- **Blog tags/related/search**: cần schema mới, hiện chỉ 2 bài blog → YAGNI.
- **Sitemap cache**: 2 blogs + 3 courses, query đã select cột tối thiểu → chưa cần.
- **Dashboard admin số liệu**: module mới ngoài scope; đề xuất tương lai khi lead nhiều.
- **Cột `job_title` cho `about`**: JSON-LD đang map từ `description` chấp nhận được; chỉ thêm nếu user muốn (ghi trong phase-e-05, mục tuỳ chọn).

## Cách test chung

Test tay trên http://127.0.0.1:8000 (public) và /admin (đăng nhập). E-05 có bước backup + verify trước/sau migration.
