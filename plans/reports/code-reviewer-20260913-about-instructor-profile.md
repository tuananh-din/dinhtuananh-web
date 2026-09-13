# Code Review — 2026-09-13 — About Instructor Profile

## Scope

- Files: `app/Http/Controllers/HomeController.php`, `resources/views/about.blade.php`, `public/site/assets/css/custom.css`, `public/site/assets/js/main.js`, `tests/Feature/AboutInstructorProfileTest.php`, `tests/Feature/PublicSeoJsonLdTest.php`.
- LOC reviewed: 4,686 current LOC; pending implementation is 739 added / 96 removed plus the new 58-line feature test.
- Focus: pending `/about` Instructor Profile only.
- Scout findings: public routes, models, layout metadata/Person JSON-LD, image accessors, CMS/Admin contract and dependent progress selector were traced before review. No other view or JS file references `.about-profile-*` or `.about-skill-progress`.

## Overall Assessment

Data visibility, optional image rendering, route use and CSS isolation are largely correct. No P0 was proved. Do not release until the P1 items below are resolved or the required owner approvals are recorded: the page otherwise can publish an incomplete testimonial set and unverified teaching/service claims.

## P0 — Critical / blocking

- None confirmed.

## P1 — Must resolve before public release

1. [`app/Http/Controllers/HomeController.php:80-94`] Testimonial ordering is not actually “featured first, then active”. When one or two featured records exist, the fallback never runs, so active records are omitted instead of filling the allowed three cards. Query all eligible active records once, order by `is_featured DESC`, `sort_order ASC`, `id DESC`, then `take(3)`; add a mixed featured/non-featured test.

2. [`resources/views/about.blade.php:86-96`] The four-step “Cách học tại đây” is rendered unconditionally although its source plan requires owner confirmation before it may be represented as the real teaching method. Hide it until confirmed, or record explicit owner sign-off before release.

3. [`resources/views/about.blade.php:73-84`] Every non-empty `Service.description` is published. The acceptance brief identifies the live Performance Marketing description as incorrect and requires its correction in Admin before it is rendered. No implementation/test establishes that correction. Update and approve the CMS record before release; do not substitute a hard-coded claim.

4. [`resources/views/about.blade.php:142-150`] `is_active` is the visibility filter, but the schema has no consent/provenance field. The brief requires owner approval for each quote, name and avatar. Obtain and record that approval, or keep the entire testimonial section hidden.

5. [`resources/views/about.blade.php:5-9`] Whitespace-only SEO/name values bypass `?:`, then `trim()` produces an empty description. This violates the required non-empty metadata fallback and can emit an empty `<title>`/description. Use `filled()`/`blank()` with trimmed values for `name`, `title_seo`, `desc_seo`, `description`, and the `about_me`→`content` fallback; add tests for blank strings.

6. [`resources/views/about.blade.php:13-16`, `resources/views/layouts/master.blade.php:35-41`] Dynamic title/OG section values are yielded raw by the layout. `AboutController@updateProfile()` accepts arbitrary `name`, which reaches `$pageTitle`; a value such as `</title><script>…</script>` becomes stored public markup. Escape values before putting them in these sections (or escape `@yield` at the layout boundary) and test an HTML/quote payload. There is no CSP in `SecurityHeaders` to mitigate this.

## P2 — Non-blocking, fix with the P1 work where practical

1. [`tests/Feature/AboutInstructorProfileTest.php:17-58`] Coverage checks only the all-empty state. Add negative cases for whitespace-only testimonial name/content, incomplete testimonial records, a mixed featured/non-featured set, and no-image case/blog cards. These are the boundary conditions of the new public filter contract.

2. [`tests/Feature/PublicSeoJsonLdTest.php:60-77`] The test validates populated SEO fields only. Add fallback-chain tests (blank `title_seo`/`desc_seo`, stripped description, neutral fallback, Setting OG fallback) and the escaping regression from P1. This protects the intended SEO contract rather than only its happy path.

3. Release validation is incomplete: Laravel tests cannot run in this workspace because `php` is absent and `vendor/autoload.php` is missing. Run the targeted suites and full `php artisan test` in the documented Laragon environment before merge. The plan remains `pending` with unchecked phase TODOs; no changelog update should be marked complete until the gates pass.

## Checklist / Adversarial Review

### Critical pass

- No new route, mutation, auth path, SQL interpolation, dependency, unbounded query, N+1 access or race condition.
- Course is filtered by `is_active=1`; CaseStudy/Blog by `is_published=1`; Blog retains its SoftDeletes global scope; Testimonial is filtered by `is_active=1` plus populated name/content. Legacy `Image(type=0)` is not used as case-study proof.
- The dynamic SEO rendering issue in P1.6 is the only newly introduced trust-boundary flaw found.

### Informational pass

- `/about` introduces no phone, email, social or address output, so it does not generate empty `tel:`/`mailto:` links or expose the private address.
- Hero is omitted when neither type-1 image nor avatar is filled; case/blog/testimonial images are conditional, reserve dimensions and are lazy below the fold. No generic placeholder is presented as proof.
- Exactly one H1 is present; section headings, route helpers, keyboard-focus style, reduced-motion rule and responsive CTA sizing are appropriate.
- New CSS is scoped to `.about-profile-*` / `.about-profile-page` (including theme and media variants). The obsolete progress CSS and observer were removed; the remaining general skill animation serves other selectors and is not referenced by `/about`.

## Positive Observations

- Public collections are bounded (3 courses; 2 case studies/blogs; 3 testimonials) and loaded in the controller, not in Blade loops.
- Empty-course and empty-proof states are honest; no fake KPI, rating, client logo, availability or self-rated skill percentage is introduced.
- Canonical route and Person JSON-LD inclusion remain intact; existing route names are used for every new CTA/detail link.

## Metrics

- Static checks: `git diff --check` PASS; `node --check public/site/assets/js/main.js` PASS.
- Laravel tests: NOT RUN — PHP CLI unavailable and `vendor/autoload.php` absent.
- Type coverage: not measured (PHP project, no configured type-coverage tool).
- Test coverage: not measured (suite cannot bootstrap).
- Linting issues: 0 from available static checks.

## Unresolved Questions

1. Has the owner approved the four-step teaching method and every public testimonial (quote, name, avatar)?
2. Has the incorrect Performance Marketing `Service.description` been corrected in Admin?
3. Is the metadata rendering contract intended to be fixed centrally in `layouts/master.blade.php` for every public page, or locally for `/about` in this scoped change?
