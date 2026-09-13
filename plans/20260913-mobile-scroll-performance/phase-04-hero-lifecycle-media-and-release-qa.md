# Phase 04 — Hero typing lifecycle và release QA

## Context links

- [Overview](./plan.md)
- [Runtime contract](./phase-01-baseline-and-runtime-contract.md)
- [Vendor/event reduction](./phase-03-event-and-asset-loading-reduction.md)
- `resources/views/home.blade.php:10-50,323-395`
- `tests/Feature/PublicTypographySemanticsTest.php`

## Overview

- Priority: P2. Status: In progress; implementation complete, release QA awaits local/browser runtime. Estimate: 3h. Depends on Phase 03.
- File ownership: homepage typing block and one focused public markup test if it is needed. No `ImageOptimizer`, `AboutController` or media-lifecycle test ownership.
- No migration, route, schema, package, image derivative/backfill or production file operation.

## Key insights

- Home typing keeps a `setInterval` alive at 100/150 ms after the hero leaves viewport. It already respects reduced motion at startup but not tab visibility or viewport visibility.
- Hero has `width`/`height`, `fetchpriority` and `decoding=async`, but only one dynamic avatar URL. Production reports a large portrait PNG; this observation is deferred to a non-blocking responsive-image follow-up, not investigated or implemented here.

## Requirements

1. Keep a static readable first word as the no-JS, native and reduced-motion fallback.
2. Start typing only when motion is allowed, the document is visible and hero text is intersecting; stop and clear its timer on `visibilitychange` or leaving the observed hero region. Resume without creating duplicate timers.
3. Use one observer/listener per homepage instance; disconnect/clear cleanly. Never alter form, anchor, JSON-LD or CMS contracts.
4. Execute the complete global QA matrix before release. Each applicable route must cover mobile menu, sticky/back-top, skip-link/in-page anchors, popup, theme, toast, form and page stack; validate feature absence only when its DOM is absent.
5. The required route groups are `/`, `/about`, `/life`, `/portfolio`, a portfolio detail, `/contact`, `/cam-on`, blog listing/detail, course listing/detail and one legacy `/{slug}.html` fixture. On coarse and reduced runs confirm raw state plus no app-created GSAP-family effects; on Article/DPM confirm native behavior.
6. Under reduced motion, explicitly test skip-link, normal anchors, back-to-top and invalid-form recovery; the last two must be instant/`auto`, not smooth. Test mobile menu, popup, theme, toast and each relevant form on the matching routes.

## Architecture

```
IntersectionObserver + visibilitychange
  -> one typing timer while hero is visible
  -> clear timer when hidden/offscreen/reduced

responsive image derivative/upload lifecycle
  -> separate non-blocking follow-up decision; not a Phase 04 change
```

## Related code files

| File | Action | Condition |
| --- | --- | --- |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/home.blade.php` | Modify | Typing lifecycle only; do not change hero image pipeline. |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/tests/Feature/PublicTypographySemanticsTest.php` | Modify | Add homepage render assertions only if they fit existing public markup scope. |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/tests/Feature/PublicPerformanceMarkupTest.php` | Create if needed | Keep under 200 lines; assert capability-safe HTML contracts, not browser FPS. |

## Implementation steps

1. Refactor the inline homepage typing code into named start/stop functions in the existing script block. Retain the first server-rendered word; avoid a new JS file.
2. Use `IntersectionObserver` for hero visibility and `document.visibilityState` for tab visibility. Ensure a timer identifier is nulled after clearing and no second interval starts on repeated observer entries.
3. In reduced-motion/unsupported-observer cases, leave the static word and return. Do not replace it with polling.
4. Add a PHP feature test that checks the homepage still renders the hero text, dimensions, form contract and required layout hooks. Test source contracts, not FPS or synthetic asset numbers.
5. Run documented PHP tests with the verified local PHP command, then repeat the entire Phase 01 route matrix on fine desktop, coarse mobile and reduced motion. Capture an explicit assertion for skip-link, anchors, back-to-top and invalid-form behavior.
6. Before release, inspect final production DOM/network after deploy for configured dynamic tags, browser errors, asset 404s, vendor order and unguarded bare globals. Keep output factual; no invented FPS/LCP declaration.

## Todo list

- [ ] Stop typing when offscreen/hidden; prevent timer duplication.
- [ ] Preserve static/reduced-motion hero fallback.
- [ ] Add/update focused markup tests.
- [ ] Run PHP/static/manual full route matrix and compare matched traces.
- [ ] Verify skip-link, anchor, back-top and invalid-form reduced-motion contract.
- [ ] Verify vendor/order release-blocker checklist is clear.

## Acceptance criteria

- Homepage has no active typing timer while hero or document is not visible.
- Reduced-motion leaves a stable readable hero; forms, anchors and title semantics remain unchanged.
- Image derivative/upload lifecycle has no implementation, test or file ownership in this plan; its observation is recorded only for a future decision.
- PHP suite and source checks pass; the full route/capability QA matrix observes no functional regression. Findings are measured observations, never assumed FPS/LCP targets.

## Risk assessment and rollback

- Visibility APIs can be unavailable: safe fallback is static text, not a permanent timer.
- Production cPanel may retain compiled views. Roll back by reverting the frontend commit, deploy, then run the approved `php artisan view:clear`; no DB reversal needed.

## Security considerations

- Do not copy values from dynamic DB snippets into tests or plan artifacts.

## Next steps

After acceptance, update project changelog/roadmap only when implementation is actually complete and the user authorizes documentation maintenance. Responsive hero derivatives remain a non-blocking, separately scoped follow-up decision.
