---
date: 2026-09-13
scope: public typography system
status: done-with-concerns
---

# Test Report — 2026-09-13 — Typography System

## Summary

- Static QA: PASS.
- Runtime PHPUnit: BLOCKED — `php` is unavailable and `vendor/autoload.php` is absent. No dependencies installed.
- Visual/responsive pass (1440/768/375/320, 200%): not run; changed source was not rendered.

## Static Checks

- `git diff --check`: PASS; no whitespace errors.
- CSS brace validation: PASS for `custom.css`, `digital-performance.css`, and `article.css`.
- Diff scope: only typography CSS and Home/Courses Blade templates; no route, controller, model, or data changes detected.
- New Be Vietnam Pro weights: PASS — only 500, 600, and 700 are declared; within required 400–700 range.
- Focus: PASS — `custom.css` loads after `main.css`; its `:focus-visible` outline uses `!important`, overriding legacy `a { outline: none !important; }`.
- Minimum labels: PASS by static cascade review. DPM legacy 10–13px labels are all included in the later scoped 14px/15px override block. Article metadata 13px label is overridden to `var(--type-meta, 14px)`. Changed Home/Courses eyebrows, badges, links, and meta labels resolve to 14px or more.

## Essential Test Correction

- Updated `tests/Feature/PublicTypographySemanticsTest.php` only. Courses Blade uses numeric HTML entities, so the test now asserts the actual response markup rather than Unicode text that would not match it.
- The correction could not be executed in PHPUnit because the local runtime/dependencies are absent.

## Recommendations

1. In a Laravel environment with PHP and Composer dependencies, run `php artisan test --filter=PublicTypographySemanticsTest`, then `php artisan test`.
2. Render the public pages before claiming visual validation at the requested viewport and zoom sizes.

## Unresolved Questions

- None. Runtime and visual verification are documented blockers, not source defects.

**Status:** DONE_WITH_CONCERNS
