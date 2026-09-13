# Pha 03 — Style, test và xác nhận phát hành

## Context links

- [Custom CSS](../../public/site/assets/css/custom.css)
- [Public SEO test](../../tests/Feature/PublicSeoJsonLdTest.php)
- [Course visibility test](../../tests/Feature/PublicCourseVisibilityTest.php)
- [Case study visibility test](../../tests/Feature/CaseStudyAdminTest.php#L83-L122)
- [Project deployment guide](../../docs/deployment-guide.md)

## Overview

- **Ngày:** 2026-09-13
- **Ưu tiên:** P1
- **Trạng thái:** Hoàn tất cài đặt; chờ xác minh runtime và visual QA
- **Mục đích:** Hoàn thiện profile ở mọi breakpoint/theme, khóa behavior bằng test và phát hành không thay contract.

## Key insights

- `custom.css` đã có style about/card/progress, light skin và asset versioning tự động qua `filemtime`; không sửa `main.css`.
- Existing `PublicSeoJsonLdTest` kiểm tra home/blog nhưng không kiểm `/about`; chưa có test cho filter/fallback profile.

## Requirements

- CSS chỉ thêm ở cuối `custom.css`, prefix `.about-profile-*`; tái dùng grid, card, `theme-btn`, Be Vietnam Pro và lime accent.
- Dark/light đều đạt AA (4.5:1 normal text); `:focus-visible` 2–4px; target thao tác tối thiểu 44px trên mobile; reduced-motion không giấu content.
- Thêm test public tập trung, không dùng fixture/claim giả làm marketing production.

## Architecture

CSS modifier tách khỏi `.about-*` legacy; tests dùng SQLite in-memory và record rõ active/published/draft. Observer progress cũ trong `main.js` đã bị gỡ cùng UI progress. Không thêm asset pipeline, analytics, request hay database migration.

## Related code files

- **Sửa:** `public/site/assets/css/custom.css`, `public/site/assets/js/main.js`, `tests/Feature/PublicSeoJsonLdTest.php`, `docs/project-changelog.md`
- **Tạo:** `tests/Feature/AboutInstructorProfileTest.php`
- **Đọc/không sửa:** `resources/views/layouts/master.blade.php`, `public/site/assets/css/main.css`

## Implementation steps

1. Append scoped layout/card/media rules for profile hero, content grid, proof cards và CTA; use `aspect-ratio`, lazy non-hero media, actual intrinsic dimensions when known; portrait above fold may use `fetchpriority="high"`. Remove the obsolete About skill-progress observer from `main.js` with the removed progress UI.
2. Add responsive rules at 320–375, 768 and 1024px: stack cards/CTA, avoid fixed width/hover-only content, keep portrait controlled. Add light/dark and focus/reduced-motion variants.
3. Đã tạo `AboutInstructorProfileTest` (dưới 200 dòng) để assert `/about` chỉ render Course active và CaseStudy/Blog published, ẩn khối evidence rỗng, giữ CTA route và không còn progress legacy.
4. Đã mở rộng `PublicSeoJsonLdTest` cho `/about`: title/meta description/canonical/OG, Person JSON-LD, fallback rỗng và escape regression.
5. Đã chạy `git diff --check`, `node --check public/site/assets/js/main.js` và kiểm tra tĩnh CSS/Blade. Chưa thể chạy targeted/full `php artisan test`: shell thiếu PHP CLI và `vendor/autoload.php`; build asset cũng chưa chạy vì dependency local không có.
6. Cần visual QA sau deploy tại 320, 375, 768, 1024, 1440px ở cả hai theme: một H1, focus order, image alt, menu/Escape, không overflow ngang, link đúng và không có nội dung giấu bởi motion.
7. Đã cập nhật changelog; không thay đổi kiến trúc/roadmap. Code review đã được xử lý trước khi bàn giao.

## Todo list

- [x] CSS chỉ ảnh hưởng profile modifiers và có biến thể cho cả hai theme.
- [ ] Targeted và full PHP test suite pass — chặn bởi PHP CLI và `vendor/` chưa có trong workspace.
- [ ] Browser/a11y/visual QA với CMS có/không có dữ liệu — thực hiện sau deploy.
- [ ] Chủ site xác nhận quyền công bố nội dung/ảnh CaseStudy đang public.
- [x] Changelog đã chuẩn bị sau khi code được duyệt tĩnh.

## Success criteria

- `/about` is usable/readable across required breakpoints/themes, with no layout shift that changes reading order.
- Test đã bổ sung để chứng minh public visibility và SEO metadata khi runtime có thể chạy; route/forms/JSON-LD vẫn không đổi.
- Deployment không cần migration, data update, config hay service mới.

## Risk assessment

- Workspace không có PHP CLI, `vendor/` hoặc dependency asset nên không thể xác minh runtime/build; không release trước khi chạy suite trong môi trường Laravel đầy đủ.
- Cần visual QA sau deploy và post-deploy smoke test `/about`; server không thay thế được kiểm thử accessibility/theme ở các breakpoint.
- CMS image dimensions vary; CSS aspect ratio must prevent CLS without cropping critical portrait content.

## Security considerations

- No new external embeds, scripts, tracking, raw fields or data collection.
- Giữ draft filters và security headers; metadata động được escape tại layout.

## Next steps

Trước release: chạy targeted và full suite trong môi trường có PHP/Composer dependency, sau đó commit/push khi chủ site yêu cầu. Sau deploy theo cPanel, visual QA + smoke-test `/about`, detail links, theme/menu; không có migration mới.
