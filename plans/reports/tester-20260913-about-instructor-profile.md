# QA Report — 2026-09-13 — About instructor profile

## Summary

- Static validation: PASS.
- Laravel feature test: NOT RUN; local PHP CLI and Composer dependencies unavailable.

## Checks

- `git diff --check`: PASS (exit 0).
- `node --check public/site/assets/js/main.js`: PASS (exit 0).
- Changed About Blade/CSS/JS contain no `about-skill-progress` or `role="progressbar"`: PASS.
- `main.js` diff removes the former `IntersectionObserver` progress animation.

## Feature-test review

- `AboutInstructorProfileTest`: hits `route('about')`; asserts public-only courses/case studies/blogs/testimonials, excludes drafts, covers the no-public-proof state, checks the contact route, and rejects legacy progress markup.
- `PublicSeoJsonLdTest::test_about_renders_its_own_seo_metadata_and_person_json_ld`: hits `route('about')`; asserts title, description, Open Graph title/description/image, canonical `http://localhost/about`, and Person JSON-LD.

## Blocker

- `which php` returned `php not found` (exit 1); `php artisan test --filter=AboutInstructorProfileTest` therefore returned exit 127.
- `vendor/autoload.php` is absent (exit 1), so even with a PHP executable the test runner cannot bootstrap until Composer dependencies are installed.
- Project instructions document a Windows Laragon binary (`C:\\App\\laragon\\bin\\php\\php-8.3.30-Win32-vs16-x64\\php.exe`), which is not executable from this macOS shell.

## Next step

- Run `php artisan test --filter=AboutInstructorProfileTest` in the Laragon environment after `composer install`, or provide a PHP CLI plus the `vendor/` directory here.

## Unresolved questions

- None beyond the unavailable local PHP/dependency environment.
