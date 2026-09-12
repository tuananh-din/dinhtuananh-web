# Code review — homepage conversion redesign

## Scope

- Files: `resources/views/home.blade.php`, `public/site/assets/css/custom.css`
- Focus: pending homepage conversion changes
- Scout findings: public data is supplied only by `HomeController@index`; legacy images replace case studies when there is no published case; the testimonial model has no field proving learner/client relationship.

## Spec compliance

- Pass: preserves existing routes, controller contract, JSON-LD, CSRF forms, honeypot fields, dark mode and responsive menu.
- Pass: courses appear before generic services; proof/testimonials are conditional; no KPI, count, availability or testimonial placeholder is fabricated.
- Gap from the original feature checklist: the current data model has no feature-to-testimonial relation and no dedicated feature media. The redesign uses published case/blog images as supporting evidence; it cannot truthfully promise per-feature social proof or visuals without CMS content/model work outside scope.

## Pre-Landing Review: 2 issues (0 critical, 2 informational)

**CRITICAL** (blocking):

- None.

**Issues** (non-blocking):

- [resources/views/home.blade.php:79] When there are only legacy `Image` records, the evidence section is rendered (`$cases->isNotEmpty()`), but this navigation card links to About and says there is no published content because it checks `$usesCaseStudies` instead.
  Fix: use the same `$cases->isNotEmpty() || $blogs->isNotEmpty()` condition as the evidence section, while retaining neutral “Hình ảnh hoạt động” wording for the legacy branch.
- [resources/views/home.blade.php:273] The headline says testimonials are from people who studied or collaborated, but `testimonials` only stores identity/content/rating and has no relationship, course or consent field.
  Fix: use a neutral heading such as “Chia sẻ được công bố trên website”, or add a verified relationship field in a separately approved phase.

## Edge cases checked

- Empty course, service, case, blog, testimonial and lead-magnet collections have safe conditional rendering or fallback CTAs.
- Legacy gallery images render as activity imagery, not case-study claims.
- Published cases link only through the existing public case-study route; draft cases remain filtered in the controller.
- Lead and lead-magnet forms retain required server field names, CSRF token and honeypot.
- No new queries, mutation, endpoint, dependency or security-sensitive route was introduced; no N+1 path is added by the view.

## Verification

- `git diff --check`: passed.
- Static directive balance: `@if/@endif`, `@foreach/@endforeach`, `@for/@endfor`, `@push/@endpush`, and `@section/@endsection`: passed.
- Static route and form-contract inspection: passed.
- Full Laravel test/build execution remains environment-blocked: PHP/Composer are absent and the sandbox cannot access the local Docker socket.

## Metrics

- Linting issues: 0 whitespace errors in changed diff.
- Test coverage: not measured in this environment.
- Type coverage: not applicable to Blade/CSS.

## Unresolved questions

- None for this scoped review; the two non-blocking items above should be addressed for the most accurate user journey.

**Status:** DONE_WITH_CONCERNS
**Summary:** No blocking security, route, form, Blade or CSS-structure regression found; two truthfulness/navigation fixes are recommended before commit.
**Concerns/Blockers:** Runtime suite and visual browser QA require the project’s PHP/Composer environment or usable Docker socket.
