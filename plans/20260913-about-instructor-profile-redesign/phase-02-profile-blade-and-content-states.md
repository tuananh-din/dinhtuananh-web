# Pha 02 — Blade hồ sơ giảng viên và trạng thái dữ liệu

## Context links

- [About view hiện tại](../../resources/views/about.blade.php)
- [Dữ liệu public Pha 01](./phase-01-public-data-and-seo-contract.md)
- [Header/footer và CTA toàn site](../../resources/views/layouts/header.blade.php), [footer](../../resources/views/layouts/footer.blade.php)

## Overview

- **Ngày:** 2026-09-13
- **Ưu tiên:** P1
- **Trạng thái:** Hoàn tất
- **Mục đích:** Thay landing “Về tôi” bằng hồ sơ rõ chuyên môn, bằng chứng có điều kiện và hành động tiếp theo có ngữ cảnh.

## Key insights

- Trang cũ dùng copy claim về personal brand/ads, render contact links rỗng và hiển thị progress như năng lực đã kiểm chứng; các phần này đã được loại bỏ.
- Layout đã có skip link, theme, menu, animation fallback và `.theme-btn`; không cần framework, form hay JavaScript mới.

## Requirements

- Giữ một H1, `@extends`, Person JSON-LD, header/footer, các route hiện có, dark mode và reveal fallback.
- Dùng ngôn ngữ trung thực: “Hồ sơ người đồng hành”, “nội dung đã công bố”, “khóa học đang hiển thị”; không dùng expert/chuyên gia/chứng chỉ/số năm/kết quả bảo đảm nếu không có field chứng minh.
- Section đã thực hiện: hero/profile → định hướng cho người học → nội dung có thể học → khóa học → case study/blog đã publish → câu chuyện từ About CMS → CTA liên hệ/khóa học.
- Không render Service, Testimonial hoặc “Cách học tại đây” vì chưa có xác nhận nội dung, phương pháp và quyền công bố.
- Dùng link `<a>` cho điều hướng, route helper cho internal URL; không thêm form, tracking hay route.

## Architecture

`about` render ba collections public độc lập. Course có empty state trung thực; khối CaseStudy/Blog chỉ xuất hiện khi có dữ liệu. Detail cards chỉ nhận title, summary/description, image accessor và route đã có. Nội dung rỗng không được thay bằng social proof/claim tĩnh.

## Related code files

- **Sửa:** `resources/views/about.blade.php`
- **Phụ thuộc:** `app/Http/Controllers/HomeController.php`
- **Không sửa:** header/footer/menu, lead/contact forms, JSON-LD partial, routes, schema

## Implementation steps

1. Đặt section metadata: title/description/OG/canonical có fallback Pha 01; giữ include Person JSON-LD không đổi.
2. Tạo hero profile bằng `image`/`avatar` có sẵn; nếu chỉ dùng ảnh fallback trang trí thì `alt=""`, nếu ảnh profile thật thì alt là tên. Không đặt danh xưng cố định “Digital media”.
3. Đã render bio từ description và HTML CMS đã duyệt (`about_me`, fallback `content`); khi thiếu bio thì bỏ section, không bịa thông tin.
4. Đã loại Service/Skill progress cũ thay vì đưa claim CMS chưa được xác minh vào profile. Không có phần trăm hoặc thanh progress tự chấm điểm.
5. Đã render course cards từ collection active với title, short description và metadata có giá trị; CTA đến `course.detail`. Khi rỗng chỉ hiện CTA đến `contact`, không nói “sắp khai giảng”.
6. Đã render CaseStudy published và Blog published như hai nhóm “Nội dung đã công bố”; case dùng `portfolio.detail`, blog dùng `blog`. Khi cả hai rỗng, bỏ khối bằng chứng.
7. Chưa render Testimonial và “Cách học tại đây”; đây là quyết định an toàn cho tới khi chủ site xác nhận quyền công bố và phương pháp thực tế.
8. Đã bỏ contact/social/address cũ; CTA dẫn `courses` hoặc `contact` bằng route hiện hữu.

## Todo list

- [x] Một H1 và hierarchy H2 theo thứ tự đọc.
- [x] Mọi card/link dùng data/filter public đúng loại.
- [x] Tất cả optional section/link/field có fallback hoặc ẩn.
- [x] Không có testimonial, KPI, rating, client/logo hay copy outcome do code tạo.
- [x] Giữ HTML CMS raw tối thiểu, chỉ nơi đã có contract.

## Success criteria

- Người dùng hiểu ai đồng hành, có thể xem course/case/blog hợp lệ hoặc liên hệ trong một lần cuộn.
- Trang rỗng không có link rỗng, placeholder review hay cấu trúc vỡ.
- Hình meaningful có alt từ CMS; icon/decorative fallback không bị screen reader đọc sai.

## Risk assessment

- CaseStudy published chỉ phản ánh trạng thái CMS; chủ site vẫn chịu trách nhiệm xác nhận quyền công bố nội dung/ảnh.
- Service, Testimonial và phương pháp học giữ ngoài trang cho đến khi có approval rõ ràng.
- Thay đổi generic CSS class/layout có thể ảnh hưởng page khác; chỉ dùng modifier `about-profile-*` ở pha 03.

## Security considerations

- Không đưa dynamic fields chưa escape vào attribute/inline script.
- Chỉ `about_me/content` được raw render theo contract cũ; tất cả dynamic text mới dùng `{{ }}`.

## Next steps

Pha 03 đã thêm style scope, tests và QC tĩnh; runtime/visual QA vẫn là gate phát hành.
