# Phase 02 — Regression, browser QA and docs handoff

## Context

- [Native markup and visual-state contract](./phase-01-native-markup-and-visual-state.md)
- Existing tests: `DigitalPerformanceLandingTest`, `FacebookCommunityLandingTest`, `DataAnalysisLandingTest`
- Local constraint: this checkout has no `vendor/` and no PHP CLI on `PATH`.

## Overview

- Priority: P1
- Status: Static validation and docs complete; runtime QA pending
- Scope: assert server-rendered disclosure contracts, then perform real-browser accessibility QA. Do not introduce a JavaScript test harness or mock native browser behavior.

## Requirements and related files

| Action | File | Coverage to add |
| --- | --- | --- |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/tests/Feature/DigitalPerformanceLandingTest.php` | DPM curriculum semantics/initial state and exact analytics label |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/tests/Feature/FacebookCommunityLandingTest.php` | Curriculum span semantics and first-open contract |
| Modify | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/tests/Feature/DataAnalysisLandingTest.php` | Curriculum span semantics and first-open contract |
| Modify after verified implementation | `/Users/Tuananh/Documents/Codex/2026-09-12/928265/work/dinhtuananh-web/docs/project-changelog.md` | Factual scope, no-route/no-migration result and any validation limit |

## Implementation and validation steps

1. Extend the existing seeded route tests; assert rendered native details/summary markup, styled module title, first-course-item `open`, retained FAQ details, and DPM's exact CPL label. Assert the old curriculum `summary > h3` pattern is absent. Keep assertions tied to current seeded/config content, not invented data.
2. Run static checks: `git diff --check`; targeted PHP tests; then `php artisan test` in a complete Laravel environment. A suitable Laragon PHP command is documented in `PROJECT-INSTRUCTIONS.md`. Record environment/output; this workspace cannot run them yet.
3. Manual browser QA on all three active routes at 1440px, 768px, 360px, 320px and 200% zoom in both themes: no clipped/overlapping title, no page horizontal scroll, data table scrolls only inside `.dpm-table-scroll`.
4. Keyboard/assistive check: Tab reaches every summary in source order; Enter and Space toggle; focus stays put; screen-reader native announcement includes meaningful label and collapsed/expanded state; decorative CSS icon is not announced. Verify two curriculum/FAQ details can remain open concurrently and data stays independent.
5. Emulate reduced motion: no new accordion animation should run. Confirm focus outline and open state remain distinguishable in light/dark.
6. After checks, append a concise changelog entry. Do not claim browser/PHP success when blocked; do not alter legacy static templates in this phase.

## Todo

- [x] Add only rendered-markup regression assertions to existing tests.
- [ ] Run targeted and full suite in a PHP/vendor-equipped environment.
- [ ] Complete responsive, keyboard, screen-reader, theme and reduced-motion QA.
- [x] Document actual outcome/limits in the changelog.

## Success criteria

- Three course routes retain their correct views and real content while rendering the repaired disclosure contract.
- PHPUnit is green in the supported environment; any failure is fixed rather than bypassed.
- Manual QA establishes every checklist item in the overview, or records a concrete blocker.

## Risks, security and release boundary

- Server-rendered tests cannot prove keyboard accessibility or computed layout; browser QA is mandatory.
- The public legacy static pages under `/site/*.html` remain an unresolved P0; they are separate from the three Laravel course routes in this phase. Their jQuery/div implementation is intentionally not altered here; owner approval is required for redirect/removal or a content-approved native rebuild.
- No auth, CSRF, personal data, external request, data store or deployment setting changes. Rollback is limited to the five UI files plus regression/docs changes.

## Next step

Request a separate owner decision for the legacy static URLs; it is not a prerequisite to safely ship the active Laravel disclosure fixes, but must not be ignored as a public exposure.
