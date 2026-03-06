# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.11.1] - 2026-03-06
### Added
- **Rapid Add Workflow:** Introduced an intelligent interception layer during asset imports to handle missing Categories and Departments.
- **`EntityCodeGeneratorService`:** Automated, collision-resistant shortcode generator with tenant-aware recursive char-shifting and sequential fallback.
- **Alpine.js Dynamic Selection:** Interactive "Rapid Add" UI including "Select All" toggles and real-time Action Button state morphing ("Create & Continue" vs "Skip & Continue").
- **Case-Insensitive Entity Matching:** Upgraded database cross-referencing to use normalized `LOWER(name)` lookups for robust existing entity detection.

### Fixed
- **Dropdown Persistence:** Resolved an issue in the Bulk Review form where mapped `category_id` and `department_id` from the Rapid Add step were being ignored by the Blade renderer.
- **UI Feedback:** Replaced CSS-dependent `peer-checked` visual states with direct Alpine.js bindings for reliable checkbox feedback.

## [0.11.0] - 2026-03-06
### Added
- **Native Heuristic Parser:** Introduced a robust, native stream-based file parser utilizing `openspout/openspout` for `Smart Import`. Allows memory-efficient row-by-row extraction from `.csv` and `.xlsx` files without hitting server memory limits.
- **Dynamic Header Detection:** Built a bilingual (English/Indonesian) heuristic algorithm capable of automatically mapping unstructured spreadsheet columns to standard database attributes.
- **Asynchronous Modal UI:** Completely overhauled the `add-asset-modal` using Alpine.js and AJAX Fetch. Includes "Floating Glass" loading overlays, real-time file size warnings (cap at 2MB), and animated UI states (`@alpinejs/collapse`).
- Comprehensive unit and feature tests validating garbage collection, header offset scans, and partial row logic within the new `SmartImportTest`.

### Changed
- **Architectural Pivot:** Completely pivoted the "Smart Import" feature away from the external Gemini AI API toward a deterministic, native server-side engine.

### Removed
- **Gemini API Pipeline:** Entirely deleted `SmartImportService` and the `services.gemini.key` configuration. All external HTTP calls for generic file JSON extraction have been securely eradicated.

## [0.10.1] - 2026-03-05
### Added
- **Nginx Infrastructure:** Transitioned the web server stack from Apache to Nginx and PHP-FPM (via Unix sockets). This architecture significantly improves throughput and reduces the memory footprint for the Multi-Tenancy application.
- **Management Container Script:** Replaced `apache-pgsql` with `nginx-pgsql` script (`/usr/local/bin/nginx-pgsql`) to seamlessly orchestrate PostgreSQL, PHP-FPM, and Nginx.
- **Tracked Infrastructure:** Captured production configuration blueprints for Nginx and PHP-FPM into the repository's `infrastructure/` directory.

### Removed
- Completely purged legacy `.htaccess` Apache routing files from `public/`.
- Obsolete `apache-pgsql` administration scripts removed from the container environment.

## [0.10.0] - 2026-03-04
### Added
- **PostgreSQL Native Migration Engine:** Replaced the legacy MariaDB foundation with a unified, high-performance PostgreSQL schema utilizing native `uuid` columns and binary `jsonb` indexing.
- **Server Management Script:** Introduced `/usr/local/bin/apache-pgsql` for robust, systemd-less container management capable of synchronizing Apache, PHP-FPM, and PostgreSQL execution states flawlessly.
- Complete data porting utility (`app:port-mariadb-to-pgsql`) for 1:1 state transitions in active environments.

### Changed
- Converted all fragile database schemas originally deploying `CHAR(36)` and `LONGTEXT` into rigid PostgreSQL native counterparts (`uuid` and `jsonb`).
- Replaced ambiguous soft-queries utilizing generic `LIKE` with PostgreSQL's strictly case-insensitive `ILIKE` operators to guarantee cross-tenant search stability.
- Explicitly enforced PHP timezone (`UTC`) and `PDO::ATTR_STRINGIFY_FETCHES => false` configurations statically within `config/database.php`.

### Security & Performance
- **Strict Foreign Key Enforcement:** Upgraded all relational constraints with explicit `cascadeOnDelete()` or `nullOnDelete()` schemas, eliminating orphan record risks system-wide.
- **`PropertyScope` Compound Indexing:** Autonomous injection of compound lookup indexes (`property_id` + `role_id/department_id/status/etc`) structurally resolving previously undetected N+1 full-table scan threats on `asset_histories` and `assets`.
- **UPSERT Idempotency Checks:** Upgraded the `TenantRestoreService` with explicit `->unique()` database-layer constraints ensuring `ON CONFLICT` merge operations complete cleanly without throwing `SQLSTATE[42P10]` exceptions during recovery states.

## [0.9.1] - 2026-03-04
### Added
- **Tenant-Aware Backup Engine:** Export logic completely rewritten via `TenantBackupService` to produce portable, UUID-relative JSON archives containing assets, attachments, and property data strictly isolated from other tenants.
- **Resilient Data Restoration:** `TenantRestoreService` seamlessly injects backup payloads into any target property, safely rebinding relational UUIDs to local endpoints while meticulously avoiding unique scope collisions.
- **Robust Cascading Deletion:** Property destruction now triggers a strictly ordered, transaction-bound waterfall delete spanning `asset_histories` → `attachments` (files unlinked from storage) → `assets` → `departments` → `categories` → `roles` → `users` → `branding images` → `property`.

### Changed
- Converted the legacy soft-delete schema for Property deletion into an authoritative force-delete algorithm for guaranteed data cleanliness.
- Redesigned the `PropertyController@destroy` view modal into a multi-layered security procedure featuring live code-matching confirmation arrays powered by Alpine.js.

### Security
- **Strict DB Transactions:** Implemented global DB rollbacks triggered by any internal structural anomaly during the `RestoreService` execution to ensure partial imports cannot desynchronise the active tenant database constraint matrix.
- Bypassed the native `PropertyScope` during cascading delete cycles via `withoutGlobalScope()` to ensure super-admins do not accidentally orphan records tied to inactive properties outside their immediate volatile session.

## [0.9.0] - 2026-03-03
### Added
- Database mapping refactored to use standard Laravel 12 UUIDs (`Illuminate\Database\Eloquent\Concerns\HasUuids`) across all primary entities (`Property`, `User`, `Role`, `Department`, `Category`, `Asset`).
- Native implicit route model binding adapted exclusively for UUID properties to completely eliminate Integer ID leakages on public-facing and internal endpoints.
- Extensive Multi-Tenancy support anchored by a new formally declared `App\Models\Scopes\PropertyScope`.
### Changed
- The `BelongsToProperty` trait was overhauled to securely bind context to the new `PropertyScope` class, dropping vulnerable inline scope declarations.
- Redesigned `PropertyScope` constraint logic to explicitly enforce strictly valid, non-null `property_id` assignments for all localized users to prevent catastrophic global data leaks during data anomaly states.
- Cleaned the testing, auditing, and remediation markdown traces from the final workspace repository.
### Security
- **IDOR Prevention:** The entire routing structure was modernized using unbreakable non-sequential UUIDs, preventing Iteration-based Insecure Direct Object Reference attacks.
- **Zero-Trust Tenant Isolation:** Global Scopes implemented at the lowest Eloquent layer structurally guarantee isolated property execution contexts. Malicious or compromised requests attempting cross-tenant injection are met with invisible data (simulated 404 behavior natively by Laravel).

## [0.8.0] - 2026-03-01
### Added
- Complete architecture migration to openSUSE Leap 16.0 within a Distrobox container.
- Native `php8-imagick` extension compiled from source via PECL to restore QR Code image generation.
### Changed
- Upgraded codebase to native PHP 8.4 and Laravel 12 API constraints.
- Switched Apache MPM architecture from `prefork` with `mod_php` to the highly scalable Event-driven MPM utilizing `php8-fpm` and `proxy_fcgi`.
- Refactored `app/Models/` Eloquent attribute bindings to the modern Laravel 12 `casts(): array` method syntax.
- Excised deprecated `HandleCors` and `CheckForMaintenanceMode` middleware from `bootstrap/app.php` to strictly comply with the slim skeleton foundation.
### Security
- Enforced strict Read-Only simulated production file environments (`root:root` with `chmod 555`) while exclusively carving write permissions for `storage/` and `bootstrap/cache/` to the web user `wwwrun`.
- Executed and validated all Laravel configuration, routing, and blade caches under the unprivileged `wwwrun` container identity.

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
