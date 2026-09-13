---
title: "Hồ sơ giảng viên / chuyên môn cho trang Giới thiệu"
description: "Đổi /about thành hồ sơ định hướng đào tạo, dùng dữ liệu CMS đã xuất bản và không thêm claim chưa kiểm chứng."
status: completed
priority: P1
effort: 1.5d
branch: master
tags: [public, about, conversion, seo, accessibility]
created: 2026-09-13
---

# Kế hoạch — About Instructor Profile

## Mục tiêu

Biến `/about` thành hồ sơ người đồng hành/giảng viên Digital Marketing: giải thích chuyên môn bằng nội dung CMS, dẫn đến khóa học hoặc liên hệ, không tự nhận “chuyên gia”, chứng chỉ, quy mô, kết quả hay số liệu không có nguồn.

## Phạm vi đã chốt

- Giữ `GET /about` (`about`), schema, seed, form, layout, menu, theme, Person JSON-LD và toàn bộ route hiện có.
- Chỉ mở rộng `HomeController@about()` để nạp Course active, CaseStudy published và Blog published; không thêm truy vấn legacy Image cho minh chứng.
- Chưa render Service, Testimonial hoặc phần “Cách học tại đây”: schema không xác nhận nội dung/phương pháp/quyền công bố tương ứng. Các phần này chỉ được xem lại sau khi chủ site xác nhận dữ liệu.
- Sửa `about.blade.php`, thêm CSS scope trong `custom.css`, thêm test public/SEO. Không sửa `routes/web.php`, migration, model hay `main.css`.
- Định vị bằng copy “đào tạo/đồng hành Digital Marketing”; danh xưng “giảng viên” chỉ là ngữ cảnh trang, không suy ra thành tích/chứng chỉ.

## Pha thực hiện

| Pha | Trạng thái | Nội dung | File |
|---|---|---|---|
| 01 | Hoàn tất | Nạp dữ liệu public tối thiểu và chốt contract SEO/fallback | [phase-01](./phase-01-public-data-and-seo-contract.md) |
| 02 | Hoàn tất | Xây lại bố cục hồ sơ, bằng chứng và CTA bằng Blade | [phase-02](./phase-02-profile-blade-and-content-states.md) |
| 03 | Hoàn tất cài đặt | CSS responsive/a11y, tests, QC, docs và handoff deploy; còn test runtime/visual QA | [phase-03](./phase-03-style-test-and-release-validation.md) |

## Dữ liệu và guardrail

- Chỉ hiển thị Course `is_active=1` (tối đa 3), CaseStudy/Blog `is_published=1` (tối đa 2/nhóm). Không có record thì ẩn khối bằng chứng tương ứng hoặc dùng empty state trung thực cho khóa học.
- Không dựng thẻ “đang cập nhật”, logo, sao, KPI, testimonial mẫu, progress hoặc phần trăm tự đánh giá.
- Không hiện điện thoại, email, social hay địa chỉ cá nhân; CTA chỉ đến route `courses` và `contact`. Nội dung HTML `about_me/content` giữ contract CMS cũ.
- Metadata `/about` có fallback không rỗng, strip HTML cho mô tả và được Blade escape tại layout trước khi vào title/meta/OG/canonical.

## Kiểm chứng và phát hành

- Đã có test cho lọc public, fallback rỗng, SEO/canonical/OG, escaping và Person JSON-LD; kiểm tra tĩnh diff/JS/CSS/Blade đã pass. `php artisan test` và build runtime chưa chạy được vì workspace thiếu PHP CLI, `vendor/` và dependency asset.
- Cần visual QA sau deploy ở 320/375/768/1024/1440px, dark/light, keyboard, reduced motion và các CTA; không có migration/route change.
- Documentation impact: minor — changelog ghi lại thay đổi đã triển khai. Các kế hoạch Phase C/D/E là lịch sử, không chặn plan này.
- Khi test runtime pass, deploy theo cPanel chuẩn; không chạy migration mới, clear compiled view/cache theo checklist hiện hữu rồi smoke-test `/about`.

## Câu hỏi còn lại

1. Chủ site xác nhận `About.description` (định vị ngắn) và `About.about_me`/`content` (câu chuyện dài) trước khi public release.
2. Nếu muốn bổ sung lại Service, Testimonial hoặc “Cách học tại đây” sau này, chủ site cần xác nhận nội dung và quyền công bố trước khi triển khai.
