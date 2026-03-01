# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.7.1] - 2026-03-01
### Added
- Inverse bidirectional Eloquent relationships (`assetHistories`, `editedAssets`, `histories`) across User and Asset models.
- Final missing string localization keys explicitly injected into `lang/en/messages.php` and `lang/id/messages.php`.
### Changed
- Converted global blade `<x-slot name="header">` injections into inner standalone floating cards on `history.blade.php` and `scan.blade.php` to respect layout hierarchy.
- Restored missing 90% opacity backdrop-blur glass classes to the responsive mobile navigation layout and component modals.
### Fixed
- Resolved `updateOrCreate` validation circumvention anti-patterns in Department, Category, and Role controllers.
- Re-patched N+1 query latency regressions in Asset and User index controllers via specific eager loading mapping.
- Eradicated trailing hardcoded English strings from Blade views, binding them safely to the localization matrices.

## [0.7.0] - 2026-03-01
### Added
- Comprehensive English/Indonesian string localization (`i18n`) across all views and error pages.
- Email Digest Notification System containing hourly and daily PDF reporting payloads for Executive Oversight.
- Database cascading delete security structures preventing orphan records.
### Changed
- Extensive UI/UX architecture overhaul strictly enforcing the "Floating Glass" design system across navigation flyouts, modals, tooltips, and all data cards.
- Refactored `store` logic in multiple controllers (Categories, Departments, Roles) to replace fragile `updateOrCreate` anti-patterns with strictly validated `create` routines.
### Fixed
- Eradicated N+1 latency loops in Asset and User index tables via eager loading.
- Replaced unstyled empty table arrays with standardized, localized empty state fallback rows.
- Patched pagination logic to visually respect active theme tokens (bg-white/90 backdrop blur vs solid backgrounds).

## [0.6.0] - 2026-02-25
### Added
- Major system overhaul including robust String-Based Modular Access (RBAC) and Executive Oversight abstraction.
- PDF exports functionality to complement existing Excel exports.
- Improved and refined Floating Glass Aesthetic UI.
### Changed
- Refactored Data abstraction and implemented DRY patterns across the codebase.
- Synced changelog and readme documentation with the latest changes.
### Security
- Enhanced overall security architecture alongside the new RBAC implementations.

## [0.5.0] - 2026-02-25
### Added
- OCR integration via OCR.space API for smart asset data extraction from images.
- Implemented Intervention Image v3 for automatic image compression (1920px max, 80% JPEG).
- Rapid-action Teleport Modals for enhanced mobile interactions.
- Upgraded QR generator to overlay dynamic property logos and Asset Tags.
### Changed
- Complete system refactoring (Phase 5 of NIHAM System Refactoring).
- Replaced archaic boolean permission flags with a new string-based permission matrix.
- Deeply linked Eloquent model observers to handle physical file deletions automatically.
### Fixed
- Cured the mobile background CSS vh-stretch defect.

## [0.4.0] - 2026-02-22
### Added
- Completely redesigned guest and auth UI utilizing a premium modern floating glass aesthetic.
- Implemented dynamic property branding features (custom logos and backgrounds per property).
- Tenancy scopes mapping natively to CSS variables and blade-rendered style tags.
- Context switching menus for Super Admins.
- Guest layout with full-screen background image and centered floating glass login card with backdrop-blur.
- App layout with fixed background image and dark overlay.
### Changed
- Navigational elements converted from standard edge-to-edge solid bars to floating pills.
- All content cards across 24 views converted into glass styling (translucency + blur).
- Reverted CHANGELOG.md to origin/main state.
### Fixed
- Constrained layout width to max-w-7xl to fix full width spanning.
- Fixed dropdown z-index layering hierarchically across navbar, header, and main content.
- Removed overflow-hidden from main content cards preventing clipping issues.

## [0.3.0] - 2026-02-22
### Added
- Property-based database separation/isolation.
### Changed
- Complete project sync (code cleanup, database).
- Synchronized documentation for release.

## [0.2.0] - 2026-02-19
### Added
- GitHub Semantic-Release CI workflow.
### Changed
- Cleanup of codebase lines.
### Fixed
- Excel export functionality fixes.

## [0.1.1] - 2025-10-18
### Added
- Initial generic Changelog integration.

## [0.1.0] - 2025-10-17
### Added
- Initial project generation and commit structure.
- MIT License added.
- Added LICENSE file.
- Revised README for NIHAM project overview and setup.
### Changed
- Database changes to make email nullable.
### Removed
- Removed asset value metric in the dashboard.
