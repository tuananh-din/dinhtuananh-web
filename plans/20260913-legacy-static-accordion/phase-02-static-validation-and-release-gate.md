# Pha 02 — Static validation and release gate

## Context

- [Pha native markup/style](./phase-01-native-static-accordion.md)
- [Project deployment constraints](../../PROJECT-INSTRUCTIONS.md#4-deploy)
- [Project changelog](../../docs/project-changelog.md)

## Overview

Priority P1. **In progress.** Source/static validation is complete. Browser, accessibility, responsive/reduced-motion and deployed-runtime QA remain pending before recording a release result. Migration: none; no Laravel test is a substitute for browser QA of direct static URLs. No Laravel route, controller, model, backend or schema changed.

## Static checks

1. Run `git diff --check`; verify all five source files have five `<details>` and exactly one `open`, and no old trigger/panel/active classes remain in their accordion markup.
2. Verify no remaining static HTML in `public/site` uses the removed handler selectors; run `node --check public/site/assets/js/main.js` after its deletion. Check CSS brace/syntax integrity and confirm `_feature.scss` and `main.css` carry the same component contract.
3. Review the diff to prove question/answer strings, headings, images and unrelated template markup did not change. Do not claim PHP/Artisan tests exercise direct static files.

## Browser and accessibility QA

Test every production-like static URL at 1440, 768, 375 and 320px plus 200% zoom:

- Mouse/touch, Tab, Enter and Space toggle each summary; focus remains visible/on the summary; no unexpected scroll.
- First item opens on first load; four start closed; open two non-first items and verify both remain open. Collapse/reopen the first.
- Confirm `+`/`−`, non-color open cue, hover and pressed state; icon does not overlap/truncate long question text or cause horizontal page scroll.
- With reduced motion enabled, opening is immediate and no disclosure transition runs. Confirm no console errors after removing handler.
- Use available screen reader/browser accessibility tree to confirm the question and collapsed/expanded state are exposed and decorative icon is ignored. Check Chromium plus Safari/Firefox if release environment supports them.

## Release and documentation

- Deploy by the existing cPanel process only; no migration. Verify all five URLs still return 200 and render the native disclosure markup after asset cache propagation.
- Append a concise factual changelog entry: five direct static legacy URLs now use native disclosures; no copy, redirect, Laravel route, backend or migration change. Do not state that the template content was approved.
- Rollback is one commit revert of the static markup/CSS/JS change if a regression is found. Do not use a redirect as an emergency substitute without the separate SEO decision.

## Unresolved content/URL decision

Accessibility repair does not establish that the English template content should remain public. Before redirect/removal/replacement, obtain owner approval for each URL's destination or retirement status and validate traffic, indexed URLs, inbound links, analytics and canonical/SEO consequences. A generic redirect, translation, fabricated Vietnamese FAQ, or deletion is out of scope.

## Todo

- [x] Pass static source and JavaScript checks.
- [ ] Complete keyboard, screen-reader, responsive and reduced-motion QA for all five URLs.
- [ ] Deploy through the existing cPanel process and complete runtime smoke QA for all five URLs.
- [ ] Record only the verified runtime result in changelog after QA; request a separate content/SEO decision.
