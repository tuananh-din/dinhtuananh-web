# Phase 01 — Baseline và runtime contract

## Context links

- [Overview](./plan.md)
- `resources/views/layouts/master.blade.php:86-175,119-126,113,243`
- `public/site/assets/js/main.js:89-95,707-803,1237-1305,1422-1435`
- `resources/views/home.blade.php:39,355-393`

## Overview

- Priority: P1. Status: Pending. Estimate: 2h.
- File ownership: performance QA only. No application/test/doc/code change in this phase.
- Purpose: establish a reproducible comparison and decide exactly which runtime dependencies can move. No migration, route, schema, backend or package change.

## Key insights

- Homepage enters the smoother branch because it does not render `is-article` or `is-dpm`.
- Production hero is a 1,524,403-byte PNG and markup has one high-priority `src`. This is recorded evidence only; responsive derivatives/upload lifecycle are explicitly deferred to a separate follow-up.
- `code_header` and `code_footer` render raw configured output. They can invalidate an asset inventory without being visible in Git.
- Puppeteer trace is unavailable. Manual browser DevTools / remote-device capture is the supported evidence path; do not install tooling only for this task.

## Requirements and decision records

1. Build the baseline/release matrix below at coarse-pointer phone, fine-pointer desktop, and `prefers-reduced-motion: reduce`. Use one real fixture for each detail/legacy category and record its resolved URL.

| Route group | Required checks when relevant |
| --- | --- |
| `/`, `/about`, `/life` | mobile menu, sticky/back-top, skip-link/anchors, theme, toast, form, page stack; homepage also hero typing/CTA. |
| `/portfolio`, portfolio detail | above globals, animation raw state and image popup. |
| `/contact`, `/cam-on` | above globals, valid/invalid form, toast and invalid-form scroll. |
| blog listing/detail | listing/detail stack; Article remains native; anchors, popup if present. |
| course listing/detail | listing/detail stack; DPM remains native; WOW/CTA/form if present. |
| legacy `/{slug}.html` | resolved legacy page/redirect, page stack and all rendered globals. |

2. For coarse/reduced runs, verify raw CSS/text/image states are already visible and DevTools confirms no app-created `ScrollSmoother`, `ScrollTrigger` or `SplitText` instance. For fine+hover, capture the corresponding enhanced desktop state.
3. For each run, preserve device/browser/network/cache settings, use the same top-to-bottom-to-top gesture, and record only observed data: Network resource list, browser Performance trace, long-task/layout/script markers, console errors and visual screenshots.
4. Build a vendor contract covering every rendered CSS `link`, JavaScript `script`, inline script, inline `on*` handler, `@stack`/`@push` dependency and bare global reference (including Toastr). Record provider, consumer route/selector, capability, and whether it must load before `main.js`; identify only tag names/origins injected by `code_header`/`code_footer`, never secrets or untrusted contents.
5. Confirm D1 with a branch experiment if necessary: native scroll and zero GSAP-family effects on coarse/no-hover/reduced-motion; enhanced effects only on fine+hover desktop. Do not use viewport width or user-agent as the classifier.
6. Audit every public Blade selector before moving a vendor. Current source evidence: `.img-popup` is case-study detail; `.wow` is footer/home/courses; `.text_invert-2` is homepage/portfolio; public Blade has no Swiper/Parallaxie/CounterUp selector match.

## Architecture and data flow

```
real device gesture
  -> browser Performance + Network evidence
  -> capability policy (coarse/no-hover/reduced vs fine desktop)
  -> Phase 02 smoother decision
  -> Phase 03 asset/init inventory
  -> Phase 04 release comparison
```

## Related code files

| File | Action | Notes |
| --- | --- | --- |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/layouts/master.blade.php` | Read only | Global asset order and dynamic slots. |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/assets/js/main.js` | Read only | Initializer and listener inventory. |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/home.blade.php` | Read only | Homepage DOM, hero and typing. |

## Implementation steps

1. Load the listed routes in the test matrix without source edits. Confirm HTTP success, expected native article/DPM behavior and homepage current smoother behavior.
2. In DevTools, capture baseline Network and a Performance recording for each matrix entry. Label date/device/cache state; compare later only against the matching baseline.
3. Inspect final DOM tags and Network initiator chain. Include all styles/scripts, inline handlers and each stack output; record dynamic configuration as “present/absent + origin/type”, not its contents.
4. Map every `main.js` GSAP/ScrollTrigger/SplitText initializer and every bare vendor global to real selectors. Separate: must retain, fine+hover desktop enhancement, coarse/reduced skip with raw state, and no public DOM use.
5. Hand Phase 02 the measured matrix and D1 outcome; hand Phase 03 the complete vendor contract, including a list of globals that must be guarded or guaranteed before `main.js`. A missing/incomparable baseline blocks vendor removal, not the mobile smoother safety change.

## Todo list

- [ ] Capture matched mobile/desktop/reduced-motion baseline.
- [ ] Record rendered dynamic script origins safely.
- [ ] Complete selector-to-vendor inventory.
- [ ] Approve D1 implementation contract.

## Success criteria

- Baseline evidence is reproducible and has no made-up threshold/FPS/LCP claim.
- Dynamic tags, styles, scripts, inline handlers, stacks and Toastr are accounted for before changing master asset tags.
- Phase 02 has an explicit all-GSAP-family capability policy; Phase 03 has the guard/order contract for every candidate vendor.

## Risk assessment and security

- Dynamic snippets can add analytics or third-party code. Treat output as untrusted; inventory it, do not alter it or expose credentials.
- A performance trace can vary. Mitigate with identical device/browser/cache conditions and multiple representative gestures, not an invented score.
- Existing plans have no blocking relation: homepage redesign is completed; legacy accordion is isolated static `/site/` work.

## Next steps

Phase 02 starts only after D1 is recorded. Responsive-image lifecycle is a separate, non-blocking follow-up; it cannot expand this plan.
