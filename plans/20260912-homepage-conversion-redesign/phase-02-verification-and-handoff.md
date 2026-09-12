# Phase 02 — Verification and handoff

## Overview

- Priority: P1
- Status: Completed with verification limits

## Validation

1. Review static Blade/tag balance, CSS diff and existing form/data contracts. **Completed.**
2. Run PHP lint plus focused public SEO, case fallback and lead tests, then the full `php artisan test` suite. **Blocked locally:** PHP runtime unavailable.
3. Build frontend assets with `npm run build`. **Blocked locally:** Vite dependencies were not available; Docker was installed but its socket was inaccessible to the sandbox.
4. Visually check 1440px and 375px: hero CTAs, cards, dark/light contrast, header/mobile menu, conditional proof, final form and footer. **Required after deploy** on the Tino host.
5. Review the final diff for accessibility, content evidence, security and accidental CMS contract changes. **Completed** for the changed Blade/CSS and no contract/schema change found.

## Deployment handoff

- Push a focused conventional commit to `origin/master` during final integration.
- In Tino cPanel: Update from Remote, Deploy HEAD Commit, then clear configuration and compiled views as prescribed by `.cpanel.yml`.
- Recheck the deployed homepage visually; the research environment observed a production 502, so deployment verification is required.

## Completion note

- No secrets or `.env` changes are included; scope remains Blade/CSS plus documentation.
- Runtime test/build status is explicitly incomplete rather than treated as passed.
- Deployment owner must run the blocked checks where PHP, Composer/Vite dependencies and database configuration are available, then verify the deployed homepage visually.
