# Phase 03 — Giảm scroll work và vendor global

## Context links

- [Overview](./plan.md)
- [Native-scroll phase](./phase-02-native-mobile-scroll-and-motion-safety.md)
- `resources/views/layouts/master.blade.php:86-105,138-176,205-243`
- `public/site/assets/js/main.js:89-126,670-722,1237-1305,1598-1834`
- `resources/views/case_study_detail.blade.php:89-97`; `resources/views/layouts/footer.blade.php:12,71`

## Overview

- Priority: P1. Status: In progress; runtime/vendor removal awaits rendered inventory. Estimate: 4h. Depends on Phase 02.
- File ownership: master asset slots/order; `main.js` non-smoother regions (sticky/back-top/WOW/cursor/coverflow/fallback); only public views proven to need a vendor stack.
- Scope stays frontend. No new package, no new JS bundle, no blanket `defer`, no asset deletion until regression QA passes.

## Key insights

- Master currently sends all theme vendor CSS/JS on all public routes, including Toastr. Existing stack scripts are placed after `main.js`, so blindly adding `defer` can run inline jQuery code before jQuery/main.
- Native handlers duplicate scroll reads. The back-to-top handler reads `scrollTop`, `window.height`, and `document.height` for every event.
- Coverflow constructors are unguarded despite no matching public Blade markup. Swiper/Parallaxie/CounterUp also lack current public selector evidence.
- WOW has real public use: footer, homepage and course listing. Its mobile/reduced behavior must reveal content immediately, not leave `.wow` hidden.

## Requirements

1. Freeze a vendor/order contract before moving any tag. It inventories all CSS links, JS scripts (including Toastr), inline scripts, inline `on*` handlers, `@stack`/`@push` output and every bare global reference. For each, record consumer route/selector and whether the provider must precede `main.js`.
2. Do not move/remove a vendor until every bare global reference is guarded **or** the provider is guaranteed in order before `main.js`/the first inline consumer. Missing guard, unresolved stack/inline dependency or unproven order is a release blocker, not a QA note.
3. Replace the two native window scroll callbacks with one passive listener plus one `requestAnimationFrame` update. Read scroll values once per scheduled update; only mutate header/back-top classes when state changes.
4. Preserve current behavior: sticky threshold remains 250 and back-top retains its current near-bottom visibility rule unless product direction explicitly changes.
5. Guard every optional initializer on both a matching DOM selector and the vendor global. Coverflow construction and event synchronisation must share one guard.
6. Initialise WOW only when matching elements exist and enhanced motion is allowed. On native/reduced paths set visible state immediately; do not wait for animation or add a second scroll observer.
7. Skip cursor work unless the visual cursor is enabled and input is fine+hover. Current `custom.css` hides it globally, so no mobile path may attach `window.onmousemove`.
8. Consume Phase 02 reduced-motion contract: back-to-top and invalid-form recovery must use instant/`auto` behavior; skip link/normal anchors remain native. Never route those paths through GSAP/jQuery smooth animation.
9. Add a dedicated vendor stack *before* `main.js` only if a public view has a confirmed feature dependency. Keep existing `@stack('scripts')` after main for current page inline code.
10. Move/remove a global vendor tag only after Phase 01 inventory proves its page use, bare-global guard and ordered dependency. Candidate-only list: Swiper CSS/JS, Parallaxie, Waypoints, CounterUp, Chroma, TextPlugin, ScrollToPlugin, Nice Select, Magnific and Toastr; case-study detail is a known Magnific consumer.

## Architecture

```
master core assets -> optional pre-main vendor stack -> defensive main.js -> existing page scripts stack
                                                \-> selector + capability checks

one passive scroll listener -> rAF state update -> sticky/back-top class mutations
```

## Related code files

| File | Action | Ownership / change |
| --- | --- | --- |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/layouts/master.blade.php` | Modify | Add ordered pre-main vendor slot; narrow only verified global tags. |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/assets/js/main.js` | Modify | Single scroll pipeline; defensive optional blocks; retain Phase 02 smoother region. |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/assets/css/custom.css` | Modify if needed | Immediate WOW/reduced-motion visibility; no redesign. |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/home.blade.php` | Modify only if vendor stack is required | Declare desktop-only GSAP/SplitText dependency, if inventory requires it. |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/portfolio.blade.php` | Modify only if vendor stack is required | Same for known `.text_invert-2`. |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/case_study_detail.blade.php` | Modify only if vendor stack is required | Declare Magnific dependency. |

## Implementation steps

1. Freeze the Phase 01 vendor map. For every master/view tag, inline handler/script and stack output, mark core, known consumer, no current consumer, dynamic-config dependency, bare global and required order. Keep uncertain tags until a manual QA route proves them safe.
2. Resolve each bare global: add a selector/global guard, or retain/provide its dependency before `main.js` and any inline consumer. Stop release if one unresolved reference/order remains.
3. Add the pre-main stack in master only after that contract exists. Do not change cache-busting for `main.js`/CSS and do not move `@stack('scripts')`.
4. Refactor sticky/back-top into a single `addEventListener('scroll', ..., { passive: true })` scheduler. Register one resize/initial-state refresh only if needed; ensure no rAF remains queued after it runs.
5. Make each optional block no-op when selector/global is absent. For coverflow, include both `new Swiper` calls and their two `.on()` bindings inside the same paired-selector guard.
6. Apply the Phase 02 capability policy to WOW/cursor and reveal content synchronously on skip paths. Keep keyboard/focus and offcanvas click behavior unchanged. Apply instant/`auto` behavior to reduced-motion back-to-top and invalid-form recovery; do not intercept skip links/normal anchors.
7. Move only confirmed per-view tags to the new pre-main stack. Retest every public consumer, including case-study image popup, mobile menu, toast messages, forms, themes, skip/anchor navigation and page-level scripts.
8. Compare matched baseline recordings. Report observed transfer/trace deltas only; do not claim synthetic FPS/LCP gains.

## Todo list

- [ ] Land one passive rAF scroll pipeline.
- [ ] Guard all optional initializers and paired coverflow code.
- [ ] Gate WOW/cursor with immediate safe visibility.
- [ ] Inventory/resolve CSS, JS, inline handlers, stacks, Toastr and bare globals before tag movement.
- [ ] Introduce ordered vendor stack without breaking inline page scripts.
- [ ] Validate instant reduced-motion back-top and invalid-form recovery.
- [ ] Scope/removal-test only inventory-proven assets.
- [ ] Execute manual consumer regression matrix.

## Success criteria

- Source contains one native scroll listener for sticky/back-to-top behavior, not two competing callbacks.
- No cursor mousemove handler on a hidden/coarse/no-hover path.
- No unguarded Swiper constructor or sync binding.
- Every removed/moved vendor has an inventory record, guarded bare global or guaranteed-before-consumer order; any missing record/guard/order blocks release.
- Reduced-motion back-to-top and invalid-form recovery are instant/`auto`; skip link and normal anchors remain functional.
- Removed/moved vendor tags have no public consumer regression, 404 or console error.
- No migration, route/schema/data/dependency change; desktop visual changes require trace-backed approval.

## Risk assessment

- Script order is the largest risk. Mitigate with one named pre-main stack, ordered dependencies, HTML assertions and route-by-route manual QA; an unresolved bare global, inline handler or stack order is a release blocker. Do not bulk add `defer`.
- Footer `.wow` means WOW cannot simply disappear globally without a desktop decision. Keep it or reveal equivalent content safely.
- Dynamic snippets may reference a vendor. Preserve any uncertain tag until the rendered production inventory identifies the actual dependency.

## Security considerations

- Do not execute, copy, or modify dynamic configured third-party snippets beyond normal browser rendering.
- Preserve external `target`/`rel`, CSRF forms and SecurityHeaders behavior; this phase changes no data boundary.

## Next steps

Phase 04 owns the homepage typing lifecycle and release evidence. It may use the now-safe capability policy; it must not reopen global vendor architecture unless QA finds a concrete regression.
