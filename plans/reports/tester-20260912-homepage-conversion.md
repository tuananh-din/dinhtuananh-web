---
type: tester
date: 2026-09-12
scope: homepage-conversion-redesign
---

# Test Report: Homepage conversion redesign

## Summary

Static QA passes for Blade structure, HTML/form contracts, accessibility fundamentals, responsive CSS, SEO integration and data fallbacks. Runtime feature tests and the production asset build could not run: PHP, Composer dependencies and Node dependencies are absent locally.

## Commands and Results

| Command | Result |
|---|---|
| `php artisan test --filter='Home|Lead|CaseStudy'` | BLOCKED — `zsh: command not found: php`; `vendor/autoload.php` also absent, so PHPUnit bootstrap cannot load. No tests executed. |
| `npm run build` | FAIL (environment) — script invokes `vite build`, then `sh: vite: command not found`; `node_modules/` absent. |
| `git diff --check -- resources/views/home.blade.php public/site/assets/css/custom.css` | PASS — exit 0, no whitespace errors. |
| Node read-only static checks | PASS — Blade directives balanced; HTML tags balanced after removing Blade/script expressions; 18 unique IDs; no duplicate IDs, missing label targets or unresolved in-page anchors. |

`phpunit.xml` uses SQLite in-memory and `vendor/autoload.php`; once dependencies and PHP are present, retry the full suite with `php artisan test` and the targeted tests below.

## Contract Validation

- Root/SEO: `HomeController@index` supplies every variable consumed by `home`; the view pushes Person JSON-LD and inherits title, description, canonical and OG metadata from `layouts.master`. Existing `PublicSeoJsonLdTest::test_home_renders_site_seo_metadata_and_person_json_ld` covers this response.
- Case-study fallback: when published case studies are absent, controller loads legacy `Image` records (`type = 0`); the view renders the alternate evidence block. Existing `CaseStudyAdminTest::test_portfolio_and_home_keep_the_legacy_image_fallback_without_published_case_studies` covers it.
- Lead/honeypot: homepage form posts CSRF, `name`, `phone`, optional `email`/`message`, `source_page=home_final_cta`, and hidden `website`. These exactly match `LeadController@store`; an occupied honeypot redirects without storing or mailing. Existing `PublicLeadSubmissionTest` covers valid submission, required fields, mail failure and honeypot behavior.
- Lead-magnet honeypot/source: existing `GroupEightCoverageTest::test_lead_magnet_subscribe_honeypot_and_source_are_covered` covers the secondary homepage form contract.

## Static Accessibility and Responsive Review

- One page H1; all new sections use `aria-labelledby`; decorative icons/shapes are hidden from assistive technology; content images carry alternative text and dimensions.
- Both forms have matched labels/IDs, appropriate autocomplete/inputmode, required state, live success/error regions, and anti-double-submit behavior inherited from the master layout.
- New card focus state is explicit. CSS contains desktop/tablet/mobile rules, 48px mobile CTA targets, single-column mobile forms, and a reduced-motion override.

## Finding

### P2 — legacy evidence is rendered but the third path card bypasses it

In the state with legacy images but no published case study and no blog, lines 193–241 render `#evidence`, but lines 79–83 link the third discovery card to `route('about')` and label it “Tìm hiểu về người đồng hành”. This weakens the evidence path only in the supported fallback state. Include `$cases->isNotEmpty()` in the card condition if the intended CTA is to reach the rendered evidence section.

## Recommendations

1. Resolve the P2 fallback-navigation mismatch, then add an assertion for the card `href="#evidence"` in the legacy-image fallback test.
2. Restore PHP/Composer and frontend dependencies, run `php artisan test` and `npm run build`, then perform browser checks at desktop, tablet and mobile widths.

## Unresolved Questions

- Is linking legacy-image-only homepages to the About page deliberate? The evidence section currently renders on the same page, indicating likely mismatch.
- Runtime output, browser accessibility tree, rendered SEO response and production asset compilation remain unverified until the missing local dependencies are restored.
