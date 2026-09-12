---
title: Homepage conversion redesign
description: Reposition the public homepage around course discovery and qualified consultation leads.
status: completed
priority: P1
effort: 1d
branch: master
tags: [homepage, ux, conversion, content]
created: 2026-09-12
blockedBy: []
blocks: []
---

# Homepage conversion redesign

## Goal

Make the homepage help a visitor choose a course, inspect published proof, or request a suitable learning path without unverified claims.

## Scope and constraints

- Keep existing public routes, controller data contract, forms, JSON-LD, dark mode and mobile menu.
- No migration, route, model or controller change.
- Do not invent testimonials, results, client names, learner counts or availability.
- Completed historical Phase C/D/E plans are non-blocking.

## Phases

| Phase | Status | Deliverable |
| --- | --- | --- |
| [01 — Homepage content and conversion flow](./phase-01-homepage-content-and-conversion-flow.md) | Completed | Revised Blade structure/copy and scoped CSS |
| [02 — Verification and handoff](./phase-02-verification-and-handoff.md) | Completed with limits | Static checks and closeout docs complete; runtime checks require deploy environment |

## Key dependencies

- CMS must contain public course/case/testimonial records before their corresponding conditional sections can appear.
- Existing `HomeController@index` variables remain the only homepage data source.

## Success criteria

- Hero has one clear primary course CTA and a distinct consultation CTA on desktop and mobile.
- Visitors see course choices before generic service descriptions.
- Proof appears only when real published CMS data exists; no testimonial placeholder remains.
- Form submission, theme switch, responsive menu, SEO metadata and existing routes retain their existing contracts.

## Completion note

- Blade/CSS static checks completed without structural errors found.
- Full PHP Artisan tests and `npm run build` were not executable locally: PHP and usable Vite dependencies were unavailable; Docker existed but the sandbox could not access its socket.
- The plan is complete as a code/documentation change. A Tino cPanel deploy must still run the runtime checks and visual review at 1440px and 375px.
