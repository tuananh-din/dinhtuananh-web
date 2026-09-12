# Release audit — homepage conversion redesign

## Status

**DONE_WITH_CONCERNS** — ready to commit/push. No code-scope, contract, review or static-integrity blocker found. Runtime validation remains a deploy-environment gate.

## Plan / scope reconciliation

| Check | Result |
| --- | --- |
| Plan status | `completed`; Phase 01 completed; Phase 02 completed with verification limits |
| Intended code scope | Met: `resources/views/home.blade.php` and `public/site/assets/css/custom.css` only |
| Documentation scope | Met: changelog, plan, phase docs and supporting reports only |
| Out-of-scope change | None: no route, controller, model, migration, dependency, lockfile or `.env` diff |
| Secret scan of changed/untracked paths | No candidate file names found |

Tracked diff: 673 additions / 281 deletions across Blade, scoped CSS and changelog. Untracked files are exclusively the homepage plan and its reports.

## Acceptance / regression audit

- Hero has a clear `#courses` primary CTA and distinct `#final-cta` consultation CTA; courses precede support topics.
- Controller data contract, JSON-LD push, named routes, CSRF, lead/lead-magnet honeypots and original field names remain present.
- Evidence and testimonials remain conditional. Legacy images are neutral “Hình ảnh hoạt động”; the testimonial heading is neutral and no KPI, availability or relationship claim was added.
- Prior QA findings resolved: the third path card now reaches `#evidence` for either legacy images or blogs; testimonial copy no longer implies verified learner/client relationship.
- `git diff --check`: pass. Static directive pairs: `@if 25/25`, `@foreach 6/6`, `@for 1/1`, `@push 2/2`, `@section 1/1`. IDs: 18 unique, no duplicate IDs or missing `aria-labelledby` target.
- Referenced named routes were verified in `routes/web.php`.

## Git readiness

- Worktree is intentionally dirty only with the scoped redesign and release documents.
- Commit remains safe to create as a focused homepage feature/documentation change.
- No conflict, unrelated user edit, generated dependency file or sensitive configuration detected.

## Blocking limits after push/deploy

1. Local `php artisan test` could not run: PHP/Composer vendor bootstrap absent.
2. `npm run build` could not run: Vite dependencies absent; sandbox cannot use Docker socket.
3. After Tino deployment, validate at 1440px and 375px: visible hero/CTA flow, course and evidence fallback states, dark/light contrast, mobile header/menu, both forms, and browser console/network errors.

## Unresolved questions

- None for commit scope. Runtime build/test and rendered visual validation must be completed on the deployment-capable environment.
