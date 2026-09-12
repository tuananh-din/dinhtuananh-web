# UX/CRO specification — homepage

Date: 2026-09-12
Scope: implementation direction only; no application code changed.

## Basis and guardrails

- Inspected: `resources/views/home.blade.php`, `app/Http/Controllers/HomeController.php`, `resources/views/layouts/header.blade.php`, `public/site/assets/css/custom.css`, `main.css`, `color.css`.
- Preserve the existing identity: graphite/dark surfaces, lime accent (`#BFF747`), rounded 18–24px cards, portrait-led hero, light/dark mode, and **Be Vietnam Pro** for Vietnamese copy.
- The production URL returned 502 in this environment and the local browser capture tool lacks Puppeteer; this is a source-led specification, not a pixel-verified production audit. Recheck desktop + 375px screenshots after deployment.
- Do not claim outcomes, client logos, student counts, certificates, partners, availability, or turnaround time unless the CMS entry and supporting evidence exist. Do not add fabricated testimonials or star ratings.

## Conversion model

Primary conversion: qualified course/consultation lead submitted through the existing final form.
Secondary conversion: a visitor opens a relevant course, case study, or published expert article.

The homepage should answer one question early: **“Tôi nên xem khóa học, xin tư vấn, hay kiểm tra năng lực trước?”** It must not make visitors decode mixed messages about personal branding, agency services, and training.

### Job-to-be-done routing

| Visitor job | Route card title | Supporting line | CTA and destination |
| --- | --- | --- | --- |
| Muốn học quảng cáo bài bản | **Tìm khóa học phù hợp** | Xem nội dung, hình thức và thông tin hiện có của từng khóa trước khi đăng ký. | **Xem khóa học** → `#courses` |
| Có bài toán doanh nghiệp cần trao đổi | **Trao đổi nhu cầu của bạn** | Mô tả ngắn mục tiêu hoặc vướng mắc để nhận tư vấn hướng đi phù hợp. | **Nhận tư vấn** → `#final-cta` |
| Cần đánh giá cách làm trước khi liên hệ | **Xem nội dung đã công bố** | Đọc bài viết chuyên môn và xem case study đã được xuất bản. | **Xem minh chứng** → `#evidence` |

Use this 3-route block immediately after the hero. It is navigation, not a promise or a feature list. Each card is a full link/button with one action only.

## Required homepage order and content

| # | Section | Exact Vietnamese copy / CTA | Content and layout rules |
| --- | --- | --- | --- |
| 0 | Sticky header | Header CTA: **Nhận tư vấn lộ trình** | Keep existing nav and theme switch. One persistent CTA only; replace “Đăng ký học” because the visitor may not have selected a course. |
| 1 | Hero | Eyebrow: **ĐÀO TẠO DIGITAL MARKETING THỰC HÀNH**; H1: **Học quảng cáo đa nền tảng, chọn đúng lộ trình cho mục tiêu của bạn**; body: **Khám phá khóa học, nội dung chuyên môn và phương án tư vấn phù hợp với nhu cầu hiện tại của bạn.**; primary: **Xem khóa học phù hợp**; secondary: **Nhận tư vấn lộ trình** | Keep left-aligned copy + right portrait and existing dark, lime-led visual language. The primary goes to `#courses`; secondary to `#final-cta`. Do not put social links in the hero’s visual priority band. Keep only evidence-backed counts and label them by their actual source, e.g. “Khóa học đang mở”, “Bài viết đã xuất bản”, “Case study đã xuất bản”. |
| 2 | JTBD routes | H2: **Bạn đang cần hỗ trợ theo hướng nào?** | Render the three route cards above. On desktop use a compact 3-up row; on mobile a single vertical route list. |
| 3 | Courses | Eyebrow: **KHÓA HỌC**; H2: **Chọn khóa học theo mục tiêu hiện tại**; lead: **Xem thông tin từng khóa để tự đánh giá mức phù hợp trước khi để lại nhu cầu.**; course CTAs: **Xem nội dung khóa học** and **Nhận tư vấn về khóa này** | Move courses before generic services, blog, and biography. The featured course gets visual priority; other active courses remain subordinate. Show platform, level, duration and price only when present in CMS; preserve the existing “liên hệ để nhận tư vấn học phí” fallback. |
| 4 | How support works | Eyebrow: **CÁCH ĐỒNG HÀNH**; H2: **Từ tự học đến trao đổi nhu cầu triển khai** | Reuse only actual `Service` records. Frame them as ways to learn or discuss a need, not guaranteed business results. End with **Trao đổi nhu cầu** → `#final-cta`. |
| 5 | Evidence | `id="evidence"`; Eyebrow: **NỘI DUNG & MINH CHỨNG**; H2: **Tìm hiểu trước khi quyết định**; CTAs: **Xem case study**, **Đọc bài viết chuyên môn** | Place published case studies and published blog posts together as proof of work and thinking. Maintain image-first cards but ensure their titles/descriptions are specific CMS content. Do not use a vague “kết quả thực tế” heading if the card does not describe a verifiable result. |
| 6 | Social proof — conditional | Eyebrow: **CHIA SẺ ĐÃ XÁC MINH**; H2: **Góc nhìn từ người đã học hoặc đã hợp tác** | Render only when there is at least one approved, active testimonial with consent. See fallback rules below. |
| 7 | Instructor / brand context | Eyebrow: **GIỚI THIỆU**; H2: **Tìm hiểu thêm về người đồng hành cùng bạn**; CTAs: **Xem hồ sơ đầy đủ**, **Gọi tư vấn** | Keep existing portrait and CMS biography. It now supports the decision instead of asking the visitor to first interpret the brand. |
| 8 | Lead magnet — conditional | CTA: **Nhận tài liệu** | Render only for an active CMS lead magnet with a truthful name and description. Keep its email form just before final conversion; never invent a download or use a generic placeholder resource. |
| 9 | Final consultation | Eyebrow: **TƯ VẤN LỘ TRÌNH**; H2: **Chưa chắc nên bắt đầu từ khóa nào?**; body: **Để lại nhu cầu hiện tại. Chúng tôi sẽ liên hệ trong giờ làm việc để trao đổi hướng phù hợp.**; submit: **Gửi nhu cầu tư vấn**; links: **Xem tất cả khóa học**, **Gọi tư vấn** | This is the only long-form lead capture area. Keep existing CSRF, honeypot, validation, and thank-you flow. Remove competing “Liên hệ tư vấn” duplicate CTA in this same action cluster; use phone as an alternate contact link only. |
| 10 | Footer | Newsletter label: **Nhận nội dung chuyên môn mới qua email**; button: **Đăng ký nhận tin** | Keep newsletter distinct from consultation. It must not imply a course registration. |

## Truthful social-proof and empty-state rules

1. A testimonial needs the original approved CMS record. Display the person’s name, role/company, avatar, rating, quote, and context only if that field is available and consented. Do not fill absent fields with a stock identity, star score, or achievement.
2. If no eligible testimonial exists, **remove the testimonial heading and placeholder card entirely**. Do not show “Đang cập nhật feedback”. The Evidence section becomes the proof path with published case studies/blog posts.
3. If published case studies exist, show only those. If the legacy image fallback has no project title, summary, or proof context, label it **Hình ảnh dự án**—not “Case study” or “Kết quả thực tế”.
4. If no case study and no published blog exist, omit the Evidence section; do not create an empty proof block. Keep a plain CTA **Nhận tư vấn lộ trình** instead.
5. Counts must be live, visible-site counts and link to the corresponding content. Never show a count as a performance outcome (for example, avoid “dự án thành công”, “học viên”, or ROI) without audited data.

## Visual system and interaction direction

- Refine rather than redesign: one editorial canvas, measured white space, image-led proof cards, no new gradients, fake logo walls, badges, carousels, or decorative motion.
- Keep dark as the atmospheric default and the existing light skin. In both themes, lime is an action accent—not body copy or decoration. On lime fills use near-black foreground (`#060606`); current light-mode white text on `#5F8F16` risks AA contrast.
- Type: keep Be Vietnam Pro for all Vietnamese UI and body copy; use 16px minimum body/input text, 1.5–1.7 line height, 600–700 heading weight, and 60–75 character desktop text measure. Do not introduce a display font that compromises Vietnamese diacritics.
- Cards: retain 18–24px radius, 1px low-contrast border, and restrained shadows. The featured course may use a lime edge/pill; all other cards should not visually compete with the hero CTA.
- CTA hierarchy: primary is a filled lime button; secondary is a border/text button. Exactly one filled primary CTA per decision area. All states (default, hover, active, disabled, keyboard focus) must remain legible in both themes.
- Keep animation purposeful. The rotating hero service word must resolve to its first static value under `prefers-reduced-motion`; scrolling/reveal content must remain visible without JS and force-finish within the existing 1.5s ceiling.

## Mobile-first implementation rules

- Design at 320–375px first; enhance at 768px and 1024px. No horizontal scroll or fixed pixel-width card/form layouts.
- Hero order stays copy → CTAs → portrait → compact evidence counts. H1 should be roughly 34–42px with a comfortable 1.08–1.15 line height; do not use a full viewport height.
- Header CTA and every button/link hit area: at least 44×44px with 8px separation. Keep a visible primary header action; the navigation remains in the existing accessible off-canvas pattern.
- At ≤767px, stack JTBD cards, course actions, evidence cards and lead inputs to one column. Primary CTA becomes full width; the supporting CTA may be full width or a clear text link below—never a cramped button pair.
- Preserve the current decision to hide decorative CTA shapes on mobile. Portrait/case/blog media need a stable `aspect-ratio` container to prevent layout jumps; no information may rely on hover.
- Final form: visible labels above fields, required marker in label text, phone field with `type="tel"`/`inputmode="tel"`, one full-width submit. Keep optional email/message clearly labelled “Không bắt buộc”.

## Accessibility and performance acceptance criteria

### Accessibility

- Keep the existing skip link, semantic landmarks and single H1; every major section uses an H2 in the table order. Use `<a>` for routes and `<button>` only for submission/toggles.
- Replace placeholder-only field identification with persistent visible labels; retain `autocomplete`. On validation failure, show field-level text, announce it with `aria-live`, and move focus to the first invalid field. Do not rely on the page-level error alone.
- Decorative line/CTA shape images use `alt=""` and `aria-hidden="true"`; meaningful portrait, course, case and testimonial images use descriptive CMS-backed alt text. Icon-only controls retain accessible names.
- Verify foreground/background pairs at WCAG 2.1 AA: 4.5:1 normal text, 3:1 large text/control boundaries. Include 2–4px `:focus-visible` treatment that is not obscured by the sticky header.
- Keyboard test: skip link → header → route cards → courses → form → footer in visual order; no keyboard trap in mobile navigation; Escape closes it.

### Performance

- Hero portrait: explicit intrinsic width/height, `fetchpriority="high"`; all non-hero content images: dimensions or CSS `aspect-ratio`, `loading="lazy"`, and responsive WebP/AVIF where the CMS pipeline supports it. The current homepage images lack reliable dimensions and should be addressed to protect CLS.
- Do not request decorative images hidden by CSS. Avoid large background assets and autoplay motion in the conversion path.
- Load the Vietnamese font used by the page with `font-display: swap`; preload only the critical Be Vietnam Pro files actually used above the fold. Do not retain unused imported display families on the landing route.
- Defer non-critical third-party/script effects. Keep animation to opacity/transform and honour reduced motion. Validate on mobile slow 4G: no visible layout shift, interactive CTA/form feedback within 100ms, and target LCP ≤2.5s / CLS ≤0.1.

## Delivery checklist

- [ ] CMS has at least one real course before a course-first claim appears.
- [ ] Every conditional section has the truthful fallback above; no placeholder testimonial or proof claim remains.
- [ ] Copy, routes, active state, dark/light contrast, 375px and 1440px layouts reviewed.
- [ ] Keyboard, screen-reader form errors, reduced motion, image dimensions/lazy loading, and slow-network page load checked.
- [ ] Analytics distinguishes `course_view`, `consultation_cta_click`, `lead_form_start`, and `lead_form_submit` without putting personal data in event labels.

## Unresolved questions

- Which courses, case studies, testimonials, lead magnet, and contact response wording are currently approved for public publication?
- Can the production 502 be resolved so the final visual review can compare the deployed page with this source-led specification?
