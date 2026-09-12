# Phase 01 — Homepage content and conversion flow

## Overview

- Priority: P1
- Status: Completed
- No migration or route changes.

## Context

- [Content evidence audit](../reports/researcher-20260912-homepage-content.md)
- [Architecture audit](../reports/researcher-20260912-homepage-architecture.md)
- [UX/CRO specification](../reports/uiux-20260912-homepage-conversion.md)

## Files to modify

- `resources/views/home.blade.php`
- `public/site/assets/css/custom.css`

## Implementation

1. Rewrite hero as digital-marketing training discovery; retain JSON-LD, `#typing-text`, portrait and accurately labelled dynamic content.
2. Add a three-card job-to-be-done routing section: course discovery, consultation and published evidence.
3. Put active courses immediately after routing, use safe course-field fallbacks and update CTA/copy.
4. Reframe existing Service records as learning/support topics with an inline consultation CTA; keep zero-data fallback useful.
5. Make case studies, blogs and testimonials conditional proof: published cases only use Case Study wording; legacy images become activity imagery; no-testimonial state is omitted.
6. Retain biography/lead magnet/final form, add visible persistent form labels and truthful final CTA copy.
7. Add scoped responsive, light/dark, hover and focus styles in `custom.css`; no new heavy media or script.

## Acceptance criteria

- One H1; major sections use H2 hierarchy and CTA links resolve to existing anchors/routes.
- The hero’s primary CTA reaches `#courses`; consultation CTA reaches `#final-cta`.
- Case/testimonial sections do not make proof claims when their published collections are empty.
- Inputs retain name, type, autocomplete, CSRF, honeypot and server-side field names; labels remain visible.
- At 375px, cards and form fields are one column with no horizontal scrolling and usable 44px actions.
- Both themes retain readable primary, secondary and focus states.

## Risks and mitigations

- CMS content may be incomplete: render conditional sections and neutral empty states.
- Existing GSAP/reveal CSS: preserve classes and verify static content remains visible.
- Copy can only make claims supported by CMS: use no KPI or testimonial fabrication.

## Completion record

- Completed in `resources/views/home.blade.php` and `public/site/assets/css/custom.css` only.
- Existing controller variables, routes, JSON-LD and lead/lead-magnet form contracts were retained; no database or backend schema change.
- Static Blade/tag and CSS diff checks completed. Runtime rendering still requires the production-capable verification recorded in Phase 02.
