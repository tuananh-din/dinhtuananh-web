---
date: 2026-09-12
scope: homepage-only conversion redesign
status: research-complete
---

# Homepage conversion redesign — architecture map

## Summary

Safe scope: change only the rendered structure/copy in `resources/views/home.blade.php` and additive/overriding rules in existing `public/site/assets/css/custom.css`. Keep `HomeController`, routes, models, layout, and `main.js` unchanged unless a concrete defect requires it. No migration or route change is needed.

`GET /` is `index` → `HomeController@index` → `home` view. The layout provides global SEO, header/footer, dark mode, mobile navigation, scroll shell, animation libraries, double-submit prevention, and versioned `main.css`/`custom.css`/`main.js` assets.

## Source map and contracts

| Concern | Exact source | Current contract to keep |
|---|---|---|
| Homepage route | `routes/web.php:41` | `GET /`, route name `index`; do not rename/move. |
| Lead POST route | `routes/web.php:62` | `POST /lead/store`, `throttle:5,1`, route `lead.store`. |
| Thank-you route | `routes/web.php:47` | Successful lead redirects to `thank.you` (`/cam-on`). |
| Page data | `app/Http/Controllers/HomeController.php:18-67` | View variables: `about`, `jobs`, `words`, `skills`, `blogs`, `cases`, `usesCaseStudies`, `featuredCourse`, `highlightCourses`, `featuredTestimonials`, `leadMagnet`. Preserve queries/order/fallbacks. |
| Global view data | `app/Providers/AppServiceProvider.php:29-43` | Every view receives first `About` as `contact` and first `Setting` as `infor`; header/footer/SEO depend on these. |
| Page sections | `resources/views/home.blade.php:6-64`, `66-130`, `132-205`, `207-273`, `275-339`, `341-400` | Hero, audiences/services, course offer, case proof, blog, testimonials/about, lead magnet + final CTA. Data-empty fallbacks are intentional. |
| Person JSON-LD | `resources/views/home.blade.php:2-4`; `resources/views/partials/jsonld-person.blade.php:1-33` | Keep the structured-data push and `$about` input. |
| Layout/assets | `resources/views/layouts/master.blade.php:22-25`, `60-112`, `115-133`, `139-245` | Cache version helper; SEO/OG/canonical; early dark theme; common header/footer/floating contact; script order and stacks. |
| Header/mobile CTA | `resources/views/layouts/header.blade.php:14-23`, `34-80`, `83-118` | Preloader markup; offcanvas IDs/classes; `#final-cta` anchor; `#theme-toggle`; `.sidebar__toggle`. |
| Footer conversion | `resources/views/layouts/footer.blade.php:21-34` | Footer anchors `#final-cta` and newsletter POST continue to work. |
| Lead server contract | `app/Http/Controllers/LeadController.php:14-60` | Honeypot `website`; required `name`/`phone`; optional email/message/source/course; invalid course is saved as null; save before best-effort notification; redirect thank-you. |

### Homepage data fields actually rendered

- `About`: `name`, `description`, `avatar`, `content`, `about_me`, `tel`, four social URLs. Schema: `database/migrations/2026_01_05_084900_create_database_table.php:30-45`, social additions `2026_07_23_000002_add_social_columns_to_about_table.php:11-27`.
- `Service`: `title`, `description`; `Skill`: `number` (only count is rendered). Schema: `2026_01_05_084900_create_database_table.php:57-63` and `2026_01_06_084049_create_skill_table.php:14-32`.
- `Course`: active/featured ordering and `title`, `slug`, descriptions, platform/level/duration, prices, CTA link/text. Schema: `database/migrations/2026_03_12_000001_create_courses_table.php:11-33`.
- `Blog`: only `is_published=1`, newest 3; `title`, `slug`, `image_url`, `description`, `created_at`. URL accessor: `app/Models/Blog.php:22-33`; publish flag: `2026_08_04_000006_add_is_published_to_blogs_table.php:9-25`.
- Cases: published `CaseStudy` newest 3; else legacy `Image(type=0)` newest 3. Keep the two rendering branches and correct link only for CaseStudy: `HomeController.php:24-28`, `home.blade.php:214-235`. Case schema: `2026_08_17_000001_create_case_studies_table.php:15-39`.
- Testimonials: active + featured sorted, otherwise active; `name`, role/company, avatar, content, rating: `HomeController.php:51-64`, `2026_03_12_000003_create_testimonials_table.php:11-23`.
- Lead magnet: newest active; current embedded form uses `lead-magnet.subscribe`: `HomeController.php:66-67`, `home.blade.php:341-344`; schema `2026_08_04_000005_create_lead_magnets_table.php:5-7`.

## Reusable UI/behaviour to retain

| Reuse | Source | Notes |
|---|---|---|
| Grid/layout | Bootstrap loaded in `layouts/master.blade.php:87`; existing `container`, `row`, `col-*`, `g-4` | Keep responsive grid; no new framework. |
| CTA | `.theme-btn` in `public/site/assets/css/main.css:208-263`; `.cta-inline` in `custom.css:56-61` | Existing hover, mobile size, dark/light handling. |
| Cards/sections | `custom.css:2-9`, `63-120`, `154-162` | Reuse `brand-card`, `placeholder-card`, `case-card`, `blog-mini-card`, `testimonial-card`, `featured-pill`, `section-shell`. |
| Hero | `home.blade.php:6-64`; `custom.css:164-194`, `1091-1141`, `1260-1283`; base responsive rules `main.css:2865-3079` | Keep `#typing-text`, `.hero-1/hero-section1`, portrait classes unless deliberately replacing their CSS. |
| Lead form | `home.blade.php:361-389`; `custom.css:196-269`, `308-309` | Retain form action/method/CSRF, `website` honeypot, `source_page=home_final_cta`, field names and `data-submit-label`. |
| Light/dark | Early selection `master.blade.php:60-70`, toggle `185-203`; overrides `custom.css:837-979`, `1070-1098` | Styling must work under `html[data-theme="light"]` and `html[data-theme="dark"]`; do not set a competing body theme. |
| Mobile menu | `header.blade.php:37-80`, `91-112`; `main.js:19-61` | Preserve IDs `mobile-menus`, `site-mobile-menu`, classes `.mobile-menus`, `.sidebar__toggle`, `.offcanvas__*`, and ARIA states. |
| Scroll/animations | Smooth wrapper `master.blade.php:119-126`; GSAP smoother `main.js:783-858`; WOW visibility fallback `master.blade.php:233-238`; text reveal `main.js:1462-1490`, universal 1.5s fallback `1831-1875` | Do not remove `#smooth-wrapper/#smooth-content`; retain `.wow` where used. If retaining `.text_invert-2`, preserve its fallback-visible behaviour. |
| Preloader | Header inline session check `header.blade.php:14-23`; CSS `main.css:532-590`; runtime `main.js:1657-1730` | Keep `.preloader`, `.preloader-text`, and 800ms/sessionStorage fallback. |
| Cache busting | `master.blade.php:22-25`, `101-105`, `175` | Any edited `main.css`, `custom.css`, or `main.js` automatically receives `?v=filemtime`. Do not replace these calls with bare `asset()`. |

## Minimal implementation path

1. Leave `routes/web.php`, `HomeController`, models, migrations, `master`, `header`, footer and `main.js` untouched.
2. Rework only `home.blade.php` section markup/copy, consuming the same variables and retaining: JSON-LD push, hero `#typing-text`, `id="courses"`, `id="final-cta"`, every fallback (`@forelse`/empty), all internal `route()` calls, and final lead/magnet form contracts.
3. Add scoped rules at end of existing `custom.css`, preferably homepage-specific selectors under `.hero-section1`, `.section-shell`, and existing card/form classes. Cover desktop, 992px/768px, and both `data-theme` modes. Avoid changes in 12k-line `main.css` unless a base-theme bug cannot be overridden.
4. Manually verify desktop + 375px: header CTA/footer CTA scroll to `#final-cta`; hamburger opens/closes and cloned MeanMenu works; theme persists after refresh; hero/final headline remains visible after 1.5s; no clipped cards/form.
5. Add focused feature coverage only if changed markup affects a behavioural contract (especially root output/data fallback). Otherwise existing tests cover route/lead/SEO paths; run the targeted suite then all tests.

## Tests and commands

Project test environment is SQLite in-memory (`phpunit.xml:20-31`). On a machine with PHP in `PATH`:

```bash
php artisan test --filter=PublicSeoJsonLdTest
php artisan test --filter=PublicLeadSubmissionTest
php artisan test --filter=LeadNotificationTest
php artisan test --filter=CaseStudyAdminTest
php artisan test
```

Laragon reference command is in `PROJECT-INSTRUCTIONS.md:8`; use its verified PHP executable if `php` is unavailable.

Relevant existing assertions:

- Home SEO/Person JSON-LD: `tests/Feature/PublicSeoJsonLdTest.php:39-55`.
- Home legacy CaseStudy/Image fallback: `tests/Feature/CaseStudyAdminTest.php:105-122`.
- Root health/status and security headers: `tests/Feature/ExampleTest.php:15-20`, `tests/Feature/SecurityHeadersTest.php:9-17`.
- Lead validation, honeypot, persistence and mail failure: `tests/Feature/PublicLeadSubmissionTest.php:17-106`; notification reply-to: `tests/Feature/LeadNotificationTest.php:14-56`.
- Lead magnet honeypot/subscription: `tests/Feature/GroupEightCoverageTest.php:33-45`.

## Risks / low-confidence assumptions

- `home.blade.php` uses `{!! $about->content !!}` and `{!! $about->about_me !!}` (`:123`, `:331`); redesign must not introduce additional unescaped dynamic fields.
- `LeadController` honeypot redirects back while successful human submissions go to thank-you; changing only Blade is safe, changing field names/action is not.
- The project instructions say CaseStudy gallery was not present, but current model/migration/test do implement `CaseStudyImage` (`app/Models/CaseStudy.php:19-22`, `2026_08_17_000002_create_case_study_images_table.php:15-27`, `CaseStudyAdminTest.php:124-200`). Homepage currently uses only hero `image`; do not infer gallery requirements from the stale documentation.
- No browser run performed in this read-only audit; the exact visual impact of existing GSAP/MeanMenu at breakpoints remains to be checked manually after implementation.

## Unresolved questions

- None blocking a homepage-only conversion redesign. Product owner should still approve any new conversion copy, proof claims, or imagery before those content changes are made.
