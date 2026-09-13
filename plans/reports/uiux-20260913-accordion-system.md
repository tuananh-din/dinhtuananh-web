---
title: Accordion and disclosure UX/a11y audit — course landings
date: 2026-09-13
scope: Read-only source audit — public course landing disclosures and legacy static public templates
status: complete-source-based
---

# Accordion / disclosure system audit

## Outcome

The three active course landing pages have a sound foundation: **33 native `<details>/<summary>` disclosures**. Keep this pattern; it gives keyboard activation and collapsed/expanded state without custom JavaScript. Do not replace it with the legacy jQuery accordion.

The implementation needs three focused corrections:

1. Remove the invalid block heading from each curriculum `<summary>`.
2. Give the analytical-data disclosure an explicit open/closed visual state.
3. Treat old HTML accordion templates as a deployment risk if they remain publicly reachable.

This is source-only. No live page, computed style, keyboard, screen-reader, or responsive-browser test was run.

## Inventory and scope

The public course route selects the three dedicated landing views by slug in [`CourseController.php`](../../app/Http/Controllers/CourseController.php#L41). Their content is real current course/FAQ copy, not placeholder content.

| Active surface | Count | Disclosure purpose | Current initial state |
| --- | ---: | --- | --- |
| Digital Performance Management | 9 curriculum + 5 FAQ + 1 data table | Session detail, pre-enrolment questions, practice data | First curriculum session open; FAQ/data table closed |
| Facebook Community Growth | 5 curriculum + 4 FAQ | Session detail, pre-enrolment questions | First curriculum session open; FAQ closed |
| Data Analysis & Visualization | 5 curriculum + 4 FAQ | Session detail, pre-enrolment questions | First curriculum session open; FAQ closed |

Evidence: curriculum and FAQ markup is in [`digital-performance.blade.php`](../../resources/views/courses/digital-performance.blade.php#L67), [`facebook-community.blade.php`](../../resources/views/courses/facebook-community.blade.php#L37), and [`data-analysis.blade.php`](../../resources/views/courses/data-analysis.blade.php#L37). The data disclosure is in [`dpm-analytics.blade.php`](../../resources/views/partials/dpm-analytics.blade.php#L1).

## Checklist assessment

| Pattern | Header | Icon | Content | States | Expand logic | Assessment |
| --- | --- | --- | --- | --- | --- | --- |
| Curriculum | Actual `Buổi 01…` number and module title | Visible `+`/`−`; decorative span is hidden from AT | One real module description | 64px minimum target, focus ring, open background, `+` becomes `−` | First item open; each item can stay open independently | Keep model; fix heading markup and add hover/pressed treatment. |
| FAQ | Actual learner question, e.g. prerequisite, format, cost, support | CSS `+`/`−` | Direct answer to that question | 64px inherited minimum, focus ring, `+` becomes `−` | All closed initially; independent multi-open | Sound for questions users may compare or revisit. |
| “Xem số liệu và cách tính” | Current label underspecifies the revealed table/CPL calculation | **No explicit icon after the native marker is removed** | Real table, caption, funnel calculation; horizontally scrollable wrapper exists | Underline is unchanged when open | Closed initially; independent | Add the same visible `+`/`−` state as FAQ; make label specific. |

Relevant styling: [`digital-performance.css`](../../public/site/assets/css/digital-performance.css#L67) removes the native marker, defines the curriculum icon/state and 64px target; FAQ icon/state is at [L119](../../public/site/assets/css/digital-performance.css#L119); the data-table summary is only underlined at [L156](../../public/site/assets/css/digital-performance.css#L156). The existing data-table wrapper already supports horizontal scrolling at [L161](../../public/site/assets/css/digital-performance.css#L161).

## Prioritized findings

| Priority | Finding | User impact | Concrete direction |
| --- | --- | --- | --- |
| P0, conditional | Five legacy public HTML files contain a different, click-only `.accordion-box` implementation: `about.html`, `faq.html`, `index-2.html`, `project-details.html`, `service-details.html`. Because they live under `public/site`, confirm whether the deployment exposes them directly. | If reachable, keyboard and assistive-technology users cannot operate the div-based trigger or learn its expanded state. The files also expose unrelated English template questions/repeated filler answers. | Prefer remove from the public web surface or redirect after owner approval. If any page must remain, rebuild with the active native `<details>` pattern and owner-approved Vietnamese content; do not translate or invent answers. |
| P1 | The curriculum uses `<h3>` inside `<summary>`. | `summary` should contain phrasing content; the heading is invalid there and can produce unreliable heading navigation/semantics. | Replace the `h3` with a styled title `span`; retain the enclosing section `h2` as the section heading. Do not add a heading role inside a native summary. |
| P1 | The data-table summary has no visual expanded/collapsed cue after global marker suppression. | Sighted users cannot quickly tell that it is an expandable control or whether the table is currently shown. | Reuse the existing decorative `+`/`−` pattern and open-state border/background. Change only the label to **“Xem bảng số liệu và cách tính CPL”**, which accurately describes the existing revenue, cost, lead and CPL table. |
| P2 | Active disclosures have focus and open states but no intentional hover/pressed state. | Desktop users get weaker affordance before clicking, particularly on FAQ rows. | Add a subtle surface/border change on `summary:hover` and `summary:active`; do not make color the only signal and do not alter the native focus ring. |
| P2 | Long module titles are currently flex items but lack an explicit shrink guard. | Titles such as “Digital Marketing Overview & Performance Marketing Mindset” need a narrow-screen/200% zoom check. | Apply `min-width:0` to the styled title, keep the number and icon fixed, and allow title wrapping; never truncate or horizontal-scroll a session title. |

The legacy handler at [`main.js`](../../public/site/assets/js/main.js#L632) listens only for mouse/touch `click` on `.acc-btn`, then uses 300ms slide motion. Its source markup uses generic `div` triggers in [`faq.html`](../../public/site/faq.html#L306), with no button, focusability, state relationship, or reduced-motion handling.

## Component contract for the active course system

Use this contract for curriculum, FAQ, and the data disclosure; it is an implementation brief, not a request to fabricate new learning content.

### Header and content

- Keep native `<details>` with `<summary>` as its first child. The full summary row is the only trigger.
- Curriculum anatomy: session number, styled text span holding the **existing module title**, then a decorative icon span with `aria-hidden="true"`. The revealed content remains the existing module description.
- FAQ anatomy: existing question text, then a decorative icon. Keep the existing answer as the revealed content.
- Data disclosure: retain the existing table, caption and calculation. Use the clearer exact label: `Xem bảng số liệu và cách tính CPL`.
- Do not add `aria-expanded`, `aria-controls`, `role="button"`, or JavaScript state mirroring to native summary: the browser exposes the native disclosure state. Do not nest links, buttons, forms, or other interactive controls inside summary.

### State and interaction rules

- Keyboard: Tab reaches every summary; Enter and Space toggle it. Focus stays on the summary after toggling.
- Visual: retain the existing 3px visible focus ring; retain `+` collapsed / `−` expanded; pair icon change with an open surface/border change. Hover/pressed is supplemental, never a replacement for focus or state.
- Course content: first module is open on initial render; users may open more than one module. Do not auto-close another session, auto-scroll, or move focus after expansion.
- FAQ/data: start closed; users may open more than one. This prevents losing an answer while comparing prerequisites, course format, cost, or supporting data.
- Static disclosures need no loading, error, or disabled state. Avoid height/slide animation; current native expansion has no custom motion. If any future animation is introduced, honor `prefers-reduced-motion: reduce`.

### Responsive rules

- Preserve the current 64px minimum summary target. At 360px, 320px, and 200% zoom, title text must wrap within the row; the session number and icon must remain visible and non-overlapping.
- Keep curriculum explanation indent aligned to the module title, but never force a width that clips text. FAQ answers may use the full content width.
- Keep the existing `.dpm-table-scroll` behavior. At narrow widths, the data table must scroll inside its wrapper, not cause page-level horizontal scroll or become clipped by `.dpm-page { overflow:hidden; }`.
- Verify both light and dark themes: text, divider, focus ring, and `+`/`−` must remain distinguishable without relying on color alone.

## Acceptance checks before release

- [ ] All 33 active disclosures work with mouse/touch, Enter, and Space; Tab order follows the document.
- [ ] Native screen-reader announcement includes each label and collapsed/expanded state; decorative icon is not announced.
- [ ] No `<h1>`–`<h6>` occurs inside `<summary>`; no nested interactive element occurs inside it.
- [ ] First curriculum item only is open on first load; all FAQ/data disclosures are closed; opening one never closes another.
- [ ] Data disclosure visibly changes between collapsed and open, and its updated label matches its actual table/CPL content.
- [ ] At 320px, 360px, 768px, 1440px, and 200% zoom: no clipped title, overlap, horizontal page scroll, or hidden focus outline.
- [ ] Light/dark and reduced-motion checks pass.
- [ ] Deployment confirms the five legacy static pages are either not public or rebuilt/removed from public reach.

## Unresolved questions

- Are the five `public/site/*.html` legacy templates reachable in the production web server? Source alone cannot determine that.
- Is one-at-a-time expansion a deliberate editorial requirement for any course? The existing active implementation permits multi-open; this audit recommends retaining that behavior.

**Status:** DONE_WITH_CONCERNS
**Summary:** Active course disclosures are native and largely accessible; the report defines the smallest semantic/state/responsive corrections and flags the separate legacy public-template risk.
**Concerns/Blockers:** No live or assistive-technology browser pass was available; static-file exposure must be verified in the deployment environment.
