# UI/UX spec — `/about` Instructor Profile

**Scope:** implementation spec only. Keep Laravel/Blade, header/footer, theme switcher, routes and Person JSON-LD. Do not make `/about` a Careers page, CV, or proof-heavy sales page.

## Design direction

Keep the existing dark graphite + lime accent and `Be Vietnam Pro`; do not introduce a new framework, stock team imagery, metric strip, blue/purple gradient or decorative motion. The page should feel like an editorial teaching profile: calm, specific, and practical. “Giảng dạy” is a page context, not an unverified credential or superlative.

Use only new, scoped selectors at the end of `public/site/assets/css/custom.css`: `.about-profile-*` (optionally body class `.about-profile-page`). Do not extend `main.css` or reuse legacy `.about-skill-progress*`.

## Required reading order and copy

### 1. Hero — position and next step

Two columns from `lg` upward: copy `7/12`, portrait `5/12`; portrait moves below copy on smaller screens.

- Eyebrow (`p`, not heading): `Giảng dạy & tư vấn Digital Marketing thực hành`
- **Only H1:** `Học Digital Marketing từ tư duy triển khai, không chỉ từ lý thuyết.`
- Lead: `Tại đây, bạn có thể xem các khóa học, khám phá nội dung chuyên môn đã công bố và trao đổi về hướng học phù hợp với mục tiêu hiện tại.`
- Primary CTA: `Xem khóa học` → `route('courses')`.
- Secondary CTA: `Trao đổi lộ trình học` → `route('contact')`.
- Portrait source remains `image->image`, then `about->avatar`. If a real profile image exists, alt is `Chân dung {{ $about->name }}`; if only a generic fallback exists, use `alt=""` and do not place it in the hero as proof. Hero gets `fetchpriority="high"`, `loading="eager"`, real intrinsic `width`/`height` when known, plus CSS `aspect-ratio` and `object-position`. No `data-speed`, parallax or copied label `Digital media`.
- Do not invent a trust cue, title, tenure, learner count, company logo, client, location or result.

### 2. “Phù hợp với bạn nếu”

Place immediately after hero as a concise three-point list; it helps visitors self-select before reading topics.

1. `Bạn mới bắt đầu và muốn hiểu cách các phần của Digital Marketing kết nối với nhau.`
2. `Bạn đang làm marketing và cần hệ thống lại cách đặt mục tiêu, theo dõi dữ liệu và rút kinh nghiệm.`
3. `Bạn đang điều hành doanh nghiệp nhỏ và muốn trao đổi marketing bằng những tiêu chí rõ ràng hơn.`

This is a static orientation aid, not an outcome promise. Desktop: numbered editorial list or narrow left rail, not three equal feature cards.

### 3. “Bạn có thể học gì ở đây?”

Use a stepped grid on desktop: topic 1 spans the wider left column, topics 2–3 stack at right; tablet may become 2 columns; phone is one column. This avoids a generic equal-card row while retaining the three requested topics.

| Topic | Exact supporting copy |
| --- | --- |
| `Xây nền Digital Marketing` | `Hiểu mục tiêu, khách hàng, kênh, nội dung và chỉ số cần theo dõi trước khi bắt đầu triển khai.` |
| `Đọc dữ liệu để ra quyết định` | `Biết đặt câu hỏi, nhận diện tín hiệu và cải thiện kế hoạch thay vì tối ưu theo cảm tính.` |
| `Kết nối online với vận hành thực tế` | `Tư duy về hành trình khách hàng, O2O/retail và cách phối hợp triển khai khi nội dung phù hợp với bài toán của bạn.` |

Use one restrained icon family already loaded (Font Awesome), always `aria-hidden="true"`; icon is supplemental, never the only meaning.

### 4. “Chuyên đề tôi có thể hướng dẫn”

Render only when `$jobs` / `Service` has records with a non-empty title and description. Display it as a two-column topic list with dividers, not a service/department roster. Do **not** overwrite, mask, or try to detect incorrect CMS copy in Blade.

CMS copy requiring owner review before publish:

| Service title | Approved-direction draft |
| --- | --- |
| `Nền tảng Digital Marketing` | `Hiểu mục tiêu, khách hàng, kênh triển khai, nội dung và cách chọn chỉ số theo từng bài toán.` |
| `Performance Marketing` | `Học cách cấu trúc chiến dịch, đọc dữ liệu, theo dõi đo lường và tối ưu quyết định marketing theo mục tiêu đã đặt ra.` |
| `Truyền thông & nội dung` | `Xây thông điệp, kế hoạch nội dung và cách phối hợp các kênh để giao tiếp nhất quán với khách hàng.` |
| `O2O / Retail Operations` | `Hiểu cách kết nối trải nghiệm online với điểm bán/vận hành khi bài toán kinh doanh cần đến.` |

If services are absent, omit the whole section and retain the course CTA; do not show “sắp cập nhật”. The current financial-market description under Performance Marketing must be corrected in Admin before it is rendered.

### 5. “Cách tôi hướng dẫn” — owner-confirmed only

This is not represented by a dedicated CMS field. Render it only after the owner confirms these describe the real method; otherwise omit the section, rather than implying a process.

1. `Hiểu bối cảnh` — `Xác định mục tiêu và điểm xuất phát.`
2. `Nắm nguyên lý` — `Học khung tư duy và cách đặt câu hỏi đúng.`
3. `Thực hành theo tình huống` — `Áp dụng vào bài tập, case hoặc tình huống phù hợp nội dung khóa học.`
4. `Đo lường & rút kinh nghiệm` — `Biết đọc kết quả, điều chỉnh và tiếp tục học.`

Use an ordered list with visible two-digit number, not a progress/timeline that implies completion or outcome.

### 6. “Khóa học hiện có”

Display up to three active records. At desktop make the first course a larger feature card and the next two a compact vertical list; with one record, center a maximum-width feature card; below `768px`, stack. Each course card contains only populated fields:

- title; `short_description` when present;
- platform, level and duration as a semantic inline list only when each field is present;
- current `sale_price` or `price` only when present (Vietnamese currency formatting is acceptable); do not label `is_active` as “đang mở đăng ký”;
- `Xem nội dung khóa học` → `route('course.detail', $course->slug)`.

Empty state (when `$courses->isEmpty()`):

> **Hiện chưa có khóa học để hiển thị.**
> Bạn có thể để lại mục tiêu để trao đổi lộ trình phù hợp.

CTA: `Trao đổi lộ trình học` → `route('contact')`. No invented date, availability or waitlist.

### 7. “Nội dung và minh chứng đã công bố”

Show the enclosing section only if `$caseStudies` or `$blogs` is non-empty. Use two independently conditional groups so an empty type never leaves a blank half.

- **Case studies:** max 2, only `is_published = true`, title, optional industry, summary, thumbnail only when its own public image exists, and `Xem case study` → `route('portfolio.detail', $caseStudy->slug)`. Do not fall back to legacy `Image(type=0)` and do not render client/KPI/logo unless owner has approved their publication.
- **Blog:** max 2, only `is_published = true`, title, optional description, optional image, and `Đọc bài viết` → `route('blog', $blog->slug)`. If a date is shown, derive it from a real timestamp and format via locale; do not fabricate a date.
- Show `Xem tất cả case study` → `route('portfolio')` or `Xem tất cả bài viết` → `route('blogs')` only for a non-empty matching group.

Cards with no image become content-only cards; no generic placeholder image presented as evidence.

### 8. “Chia sẻ được hiển thị trên website” — conditional proof

This section requires both a non-empty `$featuredTestimonials` collection and release approval that each name, quote, photo, rating and context may be published. The schema does not prove course membership or consent.

- Use max 3 `is_active = true`; order featured first, then `sort_order`, then id.
- Render only records with a non-empty `name` **and** `content`.
- Title and intro remain neutral: `Những chia sẻ đang được hiển thị trên website.` Do not call people learners, customers, employees or partners.
- Render job title/company/avatar only when the individual field exists; rating only if integer 1–5, expressed as text for assistive tech as well as decorative stars.
- No record, incomplete record, or no owner approval: omit the entire section. Never create placeholder testimonials, faces, stars or ratings.

Use a single quote treatment or asymmetric stacked quotes, not a carousel.

### 9. “Câu chuyện nghề nghiệp và lý do chia sẻ”

This section restores the human story after visitors understand the teaching offer.

- Use `about->description` once as the 1–2 sentence intro/summary.
- Use already-sanitised CMS `about->about_me` as the long-form body; fallback to existing `content` only if `about_me` is empty. Do not repeat the description in both places.
- If neither contains meaningful text, omit this section—not a made-up biography.
- Copy must explain starting point, motivation for sharing and experience that shaped the approach, after owner verification. Do not use “chuyên gia hàng đầu”, “dẫn dắt tăng trưởng đột phá”, “tập đoàn toàn cầu”, ROAS, years, learner totals or results without approved source data.

Constrain prose to `65ch`–`70ch`; rich-text children must wrap long URLs/media without horizontal overflow.

### 10. Final CTA

- H2: `Chưa rõ nên bắt đầu từ nội dung nào?`
- Body: `Xem thông tin từng khóa học hoặc gửi mục tiêu hiện tại để chọn hướng học phù hợp.`
- Primary: `Xem khóa học` → `route('courses')`.
- Secondary: `Trao đổi lộ trình học` → `route('contact')`.

Do not repeat the old contact card. Telephone/email are optional utility links only when their values exist; do not generate empty `tel:`/`mailto:` links and do not publish the private address.

## Data and rendering contract

| Data | UI rule / safe fallback |
| --- | --- |
| `$image`, `$about->avatar` | Hero only when a real profile image exists; otherwise text-first hero with no misleading visual proof. |
| `$skills` | Remove numeric `number`, percentage, `role="progressbar"`, inline `--progress`, CSS progress styles and the matching `main.js` observer. Optionally render non-empty name + description as plain “Chủ đề/năng lực được công bố”; omit it if descriptions are absent or unreliable. |
| `$jobs` | Only non-empty title + description; omit as a group if no valid items. CMS, not code, owns specialist wording. |
| `$courses` | `is_active = 1`, order `sort_order ASC`, `id DESC`, `take(3)`; course empty state above. |
| `$caseStudies` | `is_published = true`, newest first, `take(2)`; no legacy-image fallback. |
| `$blogs` | `is_published = true`, newest first, `take(2)`; hide blog group if empty. |
| `$featuredTestimonials` | Active, featured-first, `sort_order ASC`, `id DESC`, `take(3)`; owner consent remains an explicit release gate. |
| `$about->tel`, `$about->email`, `$about->address` | Render contact methods individually only if filled. Address is not needed in this profile. |

Set `/about` title/meta/OG description from `about->title_seo` / `about->desc_seo`, then a stripped, limited `about->description`, then an accurate neutral fallback such as `Thông tin giảng dạy và nội dung Digital Marketing của {{ $about->name ?: data_get($infor, 'name') }}.` Canonical stays `route('about')`; keep the current Person JSON-LD include unchanged. Do not place rich HTML in metadata.

## Layout, states and accessibility

| Viewport | Required behaviour |
| --- | --- |
| 1440 / 1024 | Constrain within existing Bootstrap container. Hero stays 7/5; content lead max `70ch`; topic grid is stepped; course first-item emphasis is preserved. |
| 768 | Hero remains readable in two columns only if portrait has enough width; otherwise stack before 767. Topic/course/evidence cards use 2 columns where no text is cramped. |
| 375 / 320 | Single column; 20px gutters minimum; section vertical padding 64–72px; H1 uses a safe clamp (roughly 38–48px); both CTAs become 100% width and at least 44px high; no negative margins, crop-critical portrait, or x-overflow. |

- Use `section` + `aria-labelledby`, `article` for course/case/blog/quote, an ordered list for method, and a `ul` for metadata. Eyebrows are paragraphs; one H1 and sequential H2/H3 only.
- Existing skip link and global `:focus-visible` are already present. Profile cards and CTA links must retain a visible 3px focus outline with 3px offset in both themes; no click handler on non-semantic elements and no nested links.
- Body text is at least 16px, line-height 1.6–1.7. Ensure dark and light text/surface pairs meet 4.5:1. Use current tokens (`var(--theme)`, `var(--header)`, `var(--body)`, `var(--white)`) plus light-theme overrides scoped to the profile.
- Give meaningful images descriptive `alt`; decorative icon elements get `aria-hidden="true"`. Images below the fold get `loading="lazy"`; all images reserve dimensions/aspect ratio to avoid CLS.
- Hover is enhancement only: subtle `transform`/border/background transition (150–220ms), active press state, and equivalent focus-visible state. Do not use `transition: all`.
- Add a scoped `@media (prefers-reduced-motion: reduce)` to remove profile transitions/transforms and omit all `wow`, parallax and progress-animation hooks from this page. Content must be visible without motion.
- Add `scroll-margin-top` to profile headings for safe anchor/focus positioning under the header.

## Current audit findings that implementation must remove

- `resources/views/about.blade.php:12–22`: generic “Về tôi” H1, internal personal-brand goal, centred banner and fixed `Digital media` label do not explain learning value.
- `resources/views/about.blade.php:33–35`: short description and long story have no semantic separation.
- `resources/views/about.blade.php:40–49`: contact block exposes address and creates phone/email routes even when fields may be blank; its primary route bypasses the requested contact flow.
- `resources/views/about.blade.php:62–85`: frames Services as client help and turns `Skill::number` into unverified score/progressbar.
- `public/site/assets/css/custom.css:725–748` and `public/site/assets/js/main.js:697–719`: progress UI and observer must disappear with the skill-percentage section; do not leave dead selectors/JS.
- `resources/views/about.blade.php:18`: image lacks intrinsic dimensions/loading priority choice. `main.css` also forces the old all-caps 100px page title; use isolated profile classes instead.

## Acceptance QA

1. In five seconds the hero answers: who the page is for, what can be learned, and the next action.
2. Confirm one H1; no `%`, `progressbar`, self-rated score, invented social proof, location proof, false availability or empty contact link.
3. Test populated and empty CMS states: inactive/draft Course/CaseStudy/Blog/Testimonial never appears; an empty optional group leaves no orphan title/blank grid.
4. At 320, 375, 768, 1024 and 1440px, verify reading order, CTA route, long title/description wrapping and no horizontal scroll in dark/light theme.
5. Keyboard-test all links, visible focus and heading anchor clearance; verify reduced-motion exposes all content; inspect image alt/loading/dimensions.

## Unresolved owner confirmations

1. Confirm that the four-step method reflects the real teaching approach before section 5 ships.
2. Approve public use of every case, KPI/client reference, testimonial quote/name/avatar/rating and its image.
3. Review CMS `Service.description`—especially Performance Marketing—and separate `About.description` (short positioning) from `About.about_me` (verified story).
