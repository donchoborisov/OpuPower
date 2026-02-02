# Changelog

This file captures the major changes made during the Laravel 12 + PHP 8.4 upgrade and the frontend migration.

## Phase 1: Backend + Admin Upgrade
- Upgraded runtime baseline to PHP 8.4 and Laravel 12.
- Replaced Voyager with Filament; admin panel now at `/admin`.
- Added admin seeder and `is_admin` access control for Filament.
- Added/updated models for `Page` and `Contact`; updated controllers and Livewire component namespace.
- Added Filament resources for Pages and Contact (Contact is view-only).
- Added `SITE_LOGO` config and updated header/home views for storage-backed images.
- Added core feature tests and updated PHPUnit config for Laravel 12 / PHPUnit 11.
- Improved Docker setup: PHP image 8.4, required extensions, and dev startup workflow.
- Added test gating and optional halt-on-failure logic in `docker/dev-up.sh`.
- Added idempotent content seeding + auto-seed fallback for required homepage pages.
- Added new env vars for admin and test/dev behaviors.

## Phase 2: Frontend (Vite + Tailwind v4)
- Migrated from Laravel Mix to Vite (added `vite.config.mjs`, removed Mix config).
- Updated Blade layouts/includes to use `@vite(...)`.
- Updated Tailwind v4 config and CSS entry to `@import "tailwindcss"` with explicit config reference.
- Converted JS entrypoints to ESM and made menu JS defensive.
- Updated Docker assets workflow to build Vite assets when manifest is missing.
- Updated Node container to Node 20 and exposed Vite dev server port.
- Documented Vite build/dev commands in README.

