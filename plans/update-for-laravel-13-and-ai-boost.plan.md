++---
name: Update package for Laravel 13 support and add Laravel Boost AI guidelines & skills
overview: Add Laravel 13 compatibility, update bundled SweetAlert2 JS, and publish Boost AI guidelines + Agent Skills for Laravel Boost integration.
todos:
  - id: research-repo-and-guidelines
    content: Deeply inspect repository, existing service provider, config, and bundled JS; read Laravel Boost third-party package AI guidelines and summarize exact files to create/modify.
    status: pending
  - id: update-composer-for-laravel-13
    content: Update `composer.json` to advertise `laravel/framework: ^13.0` compatibility; run `composer validate` and adjust PHP constraints if requested.
    status: pending
  - id: update-sweetalert2-asset
    content: Replace `resources/js/sweetalert.all.js` with the targeted SweetAlert2 distribution (identify exact upstream version), and verify blade references and `Swal.fire` usage.
    status: pending
  - id: add-boost-guidelines
    content: Add `resources/boost/guidelines/core.blade.php` describing package features, usage, publishable assets, config keys and common integration patterns (include code snippets mirroring `alert()` helper usage).
    status: pending
  - id: add-boost-skill
    content: Add `resources/boost/skills/sweet-alert-development/SKILL.md` with YAML frontmatter `name` and `description`, features list, and concise examples for tasks (publishing assets, using helpers, customizing theme).
    status: pending
  - id: docs-and-changelog
    content: Update `docs/usage.md`, `CHANGELOG.md`, and `readme.md` with Laravel 13 notes and SweetAlert2 upgrade notes; add upgrade guidance for users.
    status: pending
  - id: tests-and-ci
    content: Add minimal CI checks (composer validate, php -l or static checks) and feature test(s) verifying provider registration and asset publishing; run locally/CI.
    status: pending
  - id: release-pr
    content: Prepare a draft changelog entry and open a PR with the changes; include migration notes and a compatibility checklist for maintainers.
    status: pending
isProject: false
---

## Goal

Bring `realrashid/sweet-alert` fully compatible with Laravel 13, update the bundled SweetAlert2 JavaScript to a maintained upstream release, and add Laravel Boost third-party AI guidelines + a Boost Skill so IDE AI agents can reason about and automate usage for this package.

## Codebase context

### Patterns to mirror
- `composer.json` — current compatibility constraints and `extra.laravel.providers`/`aliases` usage.
- `src/SweetAlertServiceProvider.php` — package boot/register lifecycle and publishing groups.
- `resources/js/sweetalert.all.js` — currently bundles SweetAlert2 v11.7.20; will be replaced with the chosen upstream bundle.
- `resources/views/alert.blade.php` — blade integration that references the asset and shows `Swal.fire(...)` usage.
- `config/sweetalert.php` — package configuration keys to enumerate in guidelines.

These files teach how the package registers itself, publishes assets, and how developers consume the package from Blade.

### Existing models / migrations / relationships

None — this package does not interact with a database schema.

### Route + middleware context

This package does not register routes or middleware. Publishing is performed by the service provider using the `publishes()` method.

### Testing patterns

The repository currently lacks an automated test suite. Suggested minimal pattern:
- Use `composer validate` and PHP linting as CI gates.
- Add a basic PHPUnit or Pest feature test that boots a lightweight application, registers the service provider, and asserts that the view is loadable and that publishing paths are registered.

## Technical approach

Layers touched (in order):
1. `composer.json` — advertise Laravel 13, keep backwards compatibility where possible.
2. JS asset — replace `resources/js/sweetalert.all.js` with upstream SweetAlert2 distro; confirm API stability and update the `resources/views/alert.blade.php` CDN/asset references if the filename or initialization changed.
3. Add Boost AI guidelines — add `resources/boost/guidelines/core.blade.php` following Boost's "Third-Party Package AI Guidelines" template; include short overview, features, config, and usage snippets.
4. Add Boost Skill — add `resources/boost/skills/sweet-alert-development/SKILL.md` with YAML frontmatter and actionable examples (publish assets, use helper, confirm-delete attribute behavior).
5. Docs & changelog — update `docs/usage.md`, `CHANGELOG.md`, and top-level readme with upgrade notes.
6. CI/tests — add composer validation and at least one feature test to catch obvious regressions.

Rationale: Adding Boost guidelines + a skill lets Laravel Boost agents (Cursor, Claude Code, etc.) provide accurate code completions and generate correct integration snippets for this package; JS update ensures security/bugfixes and user expectations from upstream.

## Step-by-step implementation plan

### Step 1 — Update composer.json

**Files to create / modify:**
- `composer.json` — modify — add `^13.0` to `laravel/framework` requirement sequence.

**Key logic:**
- Keep existing broad compatibility (e.g. `^10.0|^11.0|^12.0|^13.0`). If you prefer exact ranges, mirror the upstream commit that bumped to Laravel 13.
- Run `composer validate` after edit; if `composer validate` fails due to PHP version constraints, discuss whether to raise `php` requirement.

**Mirrors:**
- Mirror the pattern already present in `composer.json` where older Laravel versions are listed; ensure `extra.laravel.providers` stays unchanged.

**Tests:**
- CI: `composer validate` passes.

---

### Step 2 — Update the bundled SweetAlert2 asset

**Files to create / modify:**
- `resources/js/sweetalert.all.js` — replace — use upstream SweetAlert2 distribution file for target version (example: replace v11.7.20 with chosen v12.x or latest stable release).

**Key logic:**
- Download the upstream `dist/sweetalert2.all.min.js` or equivalent and replace the file contents. Keep the same filename to avoid changing many references.
- Search Blade views and docs for uses of `Swal.fire` (already used) and verify no API-breaking calls exist in our usages.
- Update the file header comment to mention the new SweetAlert2 version.

**Mirrors:**
- Mirror the current file but use the newer upstream build; preserve existing `asset('vendor/sweetalert/sweetalert.all.js')` calls.

**Tests:**
- Manually load `resources/views/alert.blade.php` in a test-app environment or run a smoke test to ensure `Swal.fire` still works for `Session::pull('alert.config')` and confirm-delete handler.

---

### Step 3 — Add Laravel Boost AI Guidelines

**Files to create / modify:**
- `resources/boost/guidelines/core.blade.php` — create — short AI guideline file that follows Boost's "Third-Party Package AI Guidelines" examples.

**Key logic:**
- Provide a 4–8 paragraph guideline covering:
  - What the package does (Blade helper to show SweetAlert2 alerts; publishable assets; config keys)
  - How to install and publish assets (artisan vendor:publish tags: `sweetalert-view`, `sweetalert-config`, `sweetalert-asset`)
  - Typical usage examples: using the `alert()` helper in controllers, using `data-confirm-delete` attribute in anchors, and customizing `config('sweetalert.theme')`.
  - Any caveats for Laravel 13 (if present) or setup steps (e.g. enable `alwaysLoadJS` vs `neverLoadJS`).

**Mirrors:**
- Example template in Boost docs: put `resources/boost/guidelines/core.blade.php` and keep the content concise and prescriptive.

**Tests:**
- None (guidelines are documentation for agents), but include this file in package releases so `boost:install` picks it up.

---

### Step 4 — Add a Boost Skill

**Files to create / modify:**
- `resources/boost/skills/sweet-alert-development/SKILL.md` — create — include YAML frontmatter `name` and `description` and short actionable tasks and examples.

**Key logic:**
- Frontmatter required fields: `name` and `description`.
- Content sections: "When to use this skill", "Features", and code snippets showing how to create alert calls, how to publish assets, and how to add `data-confirm-delete` anchors.

**Mirrors:**
- Use the Boost skill examples in the docs; structure the skill so it can be picked up automatically by `boost:install`.

**Tests:**
- None, but confirm `boost:install` will detect the guidelines and skill when included in a consumer project.

---

### Step 5 — Update docs, changelog, README

**Files to create / modify:**
- `docs/usage.md` — modify — add Laravel 13 compatibility notes and SweetAlert2 version used.
- `CHANGELOG.md` — modify — add an entry for Laravel 13 support and the JS update.
- `readme.md` — modify — small note about Boost support and how to run `boost:install` to pick up package guidelines.

**Key logic:**
- Explain upgrade steps for end users, any breaking changes, and how to re-publish assets.

**Tests:**
- Visual/manual review.

---

### Step 6 — Tests & CI

**Files to create / modify:**
- `.github/workflows/ci.yml` — create — minimal CI that runs `composer validate`, `composer install --no-interaction`, and `php -l` (lint) or `vendor/bin/pest --filter` if tests added.
- `tests/Feature/ProviderRegistrationTest.php` — create — if adding tests; lightweight test to assert provider binding.

**Key logic:**
- CI ensures we do not ship invalid composer metadata and that the package loads without syntax errors.

**Tests:**
- `unauthenticated -> 401` tests are not applicable. Instead test:
  - Provider registers the `alert` singleton and alias `Alert` resolves.
  - Publishing groups include `sweetalert-view`, `sweetalert-config`, `sweetalert-asset`.

---

## Files to create or modify (complete list)

| File | Action | Mirrors | Notes |
|------|--------|---------|-------|
| `composer.json` | modify | original `composer.json` | Add `^13.0` to `laravel/framework` requirement; run `composer validate`. |
| `resources/js/sweetalert.all.js` | modify | upstream SweetAlert2 distribution | Replace with vetted upstream bundle; keep same filename to avoid downstream breakage. |
| `resources/boost/guidelines/core.blade.php` | create | Laravel Boost docs example | Third-party package AI guideline for Boost. |
| `resources/boost/skills/sweet-alert-development/SKILL.md` | create | Boost skill examples | Skill frontmatter + examples. |
| `docs/usage.md` | modify | `docs/usage.md` | Add upgrade instructions. |
| `CHANGELOG.md` | modify | existing changelog | Add Laravel 13 + SweetAlert2 upgrade notes. |
| `.github/workflows/ci.yml` | create | standard CI patterns | Run `composer validate`, `php -l`, and tests if available. |

## Test cases (complete list)

| Test | Type | Assertion |
|------|------|-----------|
| Provider registers `alert` | unit/feature | `assertTrue(app()->bound('alert'))` and facade resolves `Alert::` |
| Publishing groups present | feature | `assertContains('sweetalert-config', array_keys($provider->publishes()))` or check `artisan vendor:publish --list` output in integration test |
| SweetAlert2 asset loads | smoke | Render view containing `asset('vendor/sweetalert/sweetalert.all.js')` and assert path exists in filesystem after `vendor:publish`. |

## Open questions / decisions needed

- Which SweetAlert2 upstream version should we target (exact semver)? (e.g., `v11.7.20` -> `v12.x` or latest stable)
https://cdn.jsdelivr.net/npm/sweetalert2@11

- Do we want to bump the `php` requirement in `composer.json` (current `^7.2|^8.0|...`)? Laravel 13 may require a minimum PHP version — if so, what minimum PHP version should this package require?
laravel 13 require i think 8.3  or 8.4
- Confirm whether we should fix or refactor the `$this->app->bind(...)` call in `SweetAlertServiceProvider::register()` (it currently passes three arguments; it may be a historical pattern but deserves review).

leave it as it is.

## NOT in scope

- Implementing the full test suite for the package (beyond minimal CI and a couple of smoke tests).
- Changing public APIs of the package (we aim for a compatibility upgrade only).

---
