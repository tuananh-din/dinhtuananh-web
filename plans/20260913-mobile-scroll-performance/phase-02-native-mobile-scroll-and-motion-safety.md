# Phase 02 — Native mobile scroll và motion safety

## Context links

- [Overview](./plan.md)
- [Baseline contract](./phase-01-baseline-and-runtime-contract.md)
- `public/site/assets/js/main.js:728-803,1407-1435,1776-1834` (all GSAP/ScrollTrigger/SplitText call sites must be inventoried before implementation)
- `resources/views/layouts/master.blade.php:115-126`
- `resources/views/home.blade.php:328`

## Overview

- Priority: P1. Status: Implemented, awaiting browser/device QA. Estimate: 3h. Depends on Phase 01.
- File ownership: `public/site/assets/js/main.js`, all GSAP/ScrollTrigger/SplitText/Smoother initializer sections and the shared motion policy; the reduced-motion CSS/scroll-behavior contract. Later phases consume the policy but do not create a competing one.
- No migration, route, schema, controller, package or master wrapper deletion.

## Key insights

- `ScrollSmoother.create()` currently executes on homepage touch at `main.js:738-748`; `smoothTouch: 0.1` is opt-in smooth scrolling, not a mobile bypass.
- Article and DPM are already intentionally native. Preserve their body-class exclusion exactly.
- Homepage `.text_invert-2` currently gets SplitText plus scrubbed background-position. This is one known instance, not the scope boundary: every GSAP/ScrollTrigger/SplitText initializer is subject to the same policy.
- A reduced-motion path also must not turn navigation/form recovery into a smooth animation: skip links and normal anchors stay browser-native; back-to-top and invalid-form recovery use instant/`auto` behavior.

## Requirements

1. Define one named capability policy at the top-level of `main.js`, based on `matchMedia` only:
   - reduced motion: no enhanced scroll/motion;
   - coarse primary pointer or no hover: no enhanced scroll/motion;
   - fine-pointer **and** hover-capable, no reduced-motion: desktop candidate only.
2. Apply the policy to **every** application initializer using `gsap`, `ScrollTrigger`, `SplitText` or `ScrollSmoother`: `gsap.registerPlugin`, `gsap.config`, every tween/timeline, every `ScrollTrigger` operation, every `new SplitText`, and `ScrollSmoother.create`. Each also requires its matching DOM and vendor global; loading an inert vendor script is not permission to initialize it.
3. On coarse/no-hover/reduced paths, create none of those effects. Raw CSS/text/image states must be immediately visible, readable and operable without a timeout, split child, transform, opacity or animation completion.
4. Gate `ScrollSmoother.create()` on that policy plus the existing shell/article/DPM conditions. Do not reference a nonexistent smoother in refresh callbacks or child animation configuration.
5. Keep smooth wrapper markup intact in this phase. It is harmless structural markup and reduces blast radius; removal is not required for native browser scrolling.
6. Define the reduced-motion scroll contract consumed by Phase 03: do not intercept skip links/normal anchors; back-to-top and invalid-form recovery must use `behavior: 'auto'`/instant, never GSAP, jQuery or CSS smooth behavior.

## Architecture

```
matchMedia policy
  ├─ coarse | no-hover | reduced -> native browser scroll; raw DOM/CSS visible; no GSAP-family effects
  └─ fine + hover + no-reduced -> desktop effects only when matching DOM and globals require them

all paths -> Article/DPM remain native by existing body-class rule
```

## Related code files

| File | Action | Ownership / boundary |
| --- | --- | --- |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/assets/js/main.js` | Modify | Single capability helper and every GSAP/ScrollTrigger/SplitText/Smoother initializer it controls. |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/public/site/assets/css/custom.css` | Modify only if needed | Reduced-motion/raw-state override; no visual redesign. |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/layouts/master.blade.php` | Read only | Keep `#smooth-wrapper`/`#smooth-content`. |
| `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/resources/views/home.blade.php` | Read only | Verify CTA heading remains text-first. |

## Implementation steps

1. Add a named, defensive capability helper. Use optional `window.matchMedia`; default conservatively to native behavior if it is unavailable.
2. Inventory all GSAP-family call sites first, then put every effect initializer behind the one helper, matching DOM and available globals. Do not leave a second mobile-specific exception.
3. Replace the current smoother condition with: existing shell + not Article + not DPM + enhanced-scroll capability + `ScrollSmoother` available. Scope the refresher to an existing instance; do not add global resize listeners as a substitute.
4. For `.text_invert`/`.text_invert-2` and every other effect selector, make the unanimated source state the default. Build split text/tweens/triggers only in the enhanced branch.
5. Add/confirm a `prefers-reduced-motion` CSS override where an existing global smooth-scroll style could override instant behavior. Phase 03 must use this shared policy for back-to-top and invalid-form scroll.
6. In coarse and reduced DevTools runs, verify visual raw states and zero application-created smoother/trigger/split effects (including `ScrollTrigger.getAll()` where the vendor exposes it). In fine+hover, verify only DOM-backed desktop effects exist.
7. Test skip link and in-page anchors now; Phase 03/04 must additionally validate reduced-motion back-to-top and invalid-form recovery with instant/`auto` behavior.

## Todo list

- [ ] Add capability helper with conservative fallback.
- [ ] Gate every GSAP-family initializer and smoother-specific refresh work.
- [ ] Verify raw text/image/CSS states on coarse/reduced paths.
- [ ] Define/cover instant reduced-motion scroll contract.
- [ ] Verify zero app-created smoother/trigger/split effects on coarse/reduced paths.
- [ ] Run target PHP markup tests and manual matrix before Phase 03.

## Success criteria

- Coarse and reduced-motion paths never instantiate an application `ScrollSmoother`, `ScrollTrigger`, `SplitText`, GSAP tween or timeline; raw state remains visible.
- Desktop effects run only with fine pointer + hover + no reduced-motion, the matching DOM and loaded globals.
- Article/DPM behavior is unchanged; no console exceptions from undefined globals/instances.
- Skip link/anchors remain functional; the documented reduced-motion contract requires `auto`/instant back-to-top and invalid-form recovery after Phase 03.
- No route, migration, schema, asset deletion or package change.

## Risk assessment

- `matchMedia` capabilities can change after input hardware changes. Apply policy at page load; a reload is acceptable and simpler than live smoother teardown/recreation.
- Skipping SplitText can expose CSS intended for split children. Verify both themes and retain raw semantic text as the baseline.
- ScrollSmoother may have hidden dependencies on other plugin registration. Guard each global and use Phase 03 inventory before removing a tag.

## Security considerations

- Client capability detection reads no personal data and persists nothing.
- Do not change `code_header`/`code_footer` rendering or relax CSP/security headers.

## Next steps

Phase 03 may consume the capability helper but owns listener/vendor changes. Do not merge both phases into one large `main.js` rewrite.
