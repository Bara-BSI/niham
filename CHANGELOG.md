# Changelog

All notable changes to this project will be documented in this file.

The format is based on "Keep a Changelog" and follows Semantic Versioning.

## [Unreleased]
### Added
- Placeholder for upcoming features, endpoints, controllers, views, or packages.
- Add new migrations, jobs, events, or queued workers.

### Changed
- Notes about refactors, dependency upgrades, or breaking changes.

### Fixed
- Bug fixes and small improvements.

### Security
- Security-related fixes and hardening.

## [0.6.0] - 2026-02-25
### Added
- **Google Socialite Integration:** Seamless Single Sign-On (SSO) authentication allowing users to log in securely via their Google Workspace accounts.
- **Asset History Tracking:** Comprehensive chronological logging of all asset events (creation, modifications, status changes, and soft deletions) managed natively by `AssetObserver`.
- **Advanced Export Engine:** Upgraded asset reporting to generate beautifully formatted PDF exports (using mPDF/DomPDF) alongside standard Excel spreadsheets, complete with UI export selector modals.
- **Progressive UI Disclosure:** Intelligently hides/shows action buttons (Edit, Delete, Status Update) natively based on the user's specific Role and Department permissions across all views.
- **Custom Themed Error Pages:** Replaced default Laravel exception pages (403, 404, 500) with custom-designed glassmorphism Error Pages that natively adhere to the active property's branding.
- **Department Oversight System:** Granular RBAC enhancement enabling specific users/roles to natively oversee and manage users and assets *across* departments without breaking physical property scopes.

### Changed
- **Massive DRY UI Refactoring:** Surgically abstracted thousands of lines of duplicated Blade Boilerplate logic into reusable `<x-hover-card>`, `<x-modal-export>`, and `<x-modal-update-status>` Laravel Blade components.
- **Expansive Desktop Layouts:** Radically refactored the constrained `max-w-4xl` layouts across `assets/show`, `users/show`, `departments/show` into massive `max-w-6xl` dual-column flex experiences for desktop monitors.
- **Client-Side Image Compression:** Asset image uploads are now intelligently downscaled to 1920px (JPEG format) directly within the user's browser via JavaScript *before* hitting the server.
- **Strict Login Validation:** Hardened the login interfaces, actively stripping standard password recovery links and enforcing strict database-only validation rules inside the GoogleLoginController.

### Fixed
- **Dashboard Data Leakage:** Secured the `DashboardController` ensuring non-executive users can no longer see property-wide aggregate metrics without explicit departmental authorization.
- **Hover Card Image Null-Pointer:** Fixed the `first() on null` Blade compilation crash when hovering over assets missing attachment relations.
- **Database Seeder Integrity:** Synchronized the `DatabaseSeeder` with the rewritten `v0.5.0` granular string-based permission schemas.
- **Component Scope Isolation:** Resolved Alpine Javascript collisions by cleanly detaching `x-teleport` modals from constrained table data blocks.

### Security
- **Backup Controller Lockdown:** Explicitly patched severe authorization bypasses inside `BackupController`, ensuring standard users cannot invoke database extraction dumps.
- **Property Context Hijacking:** Secured the `PropertyController` to definitively reject property-switching POST requests from all users lacking the explicit Super Admin designation.
- **OCR Client Crash Resilience:** Fortified the frontend Javascript `fetch` loop to elegantly trap and alert HTTP 500 exceptions from the OCR endpoint without corrupting Alpine component state.
## [0.5.0] - 2026-02-25
### Added
- **AI-Powered OCR Scanning:** Integrated OCR.space API to intelligently parse uploaded asset images and auto-fill Asset Name, Brand, and Serial Number fields.
- **Granular String-Based Permissions:** Completely replaced the legacy boolean permission system (`can_create`, etc.) with a modular string matrix (`perm_assets`, `perm_users`, etc.).
- **Executive Oversight Roles:** Added a new `is_executive_oversight` flag to Departments, definitively resolving hardcoded checks.
- **Intervention Image Processing (v3):** Asset image uploads are now automatically compressed to a maximum of 1920px (80% JPEG quality) to dramatically reduce storage bloat.
- **Advanced QR Code Architectures:** Generated QR codes now dynamically embed the active Property's logo in the center and automatically render the `Asset Tag` explicitly underneath.
- **Rapid-Action UI Modals:** Created a highly responsive "Update Status" rapid-action teleport modal accessible directly from the asset views.

### Changed
- **Eloquent File Deletion:** Offloaded physical file deletion from Controller methods directly to Eloquent `deleting` and `forceDeleted` events on the `Asset` model.
- **Policy Overhauls:** All 5 core system Policies (`AssetPolicy`, `UserPolicy`, etc.) have been completely rewritten to check the new `User::hasPermission()` and `User::hasExecutiveOversight()` abstractions.

### Fixed
- **Mobile Background Stretching Defect:** Replaced erratic CSS `bg-fixed` implementations on `app.blade` and `guest.blade` with flawless full-screen `<img class="object-cover w-full h-full">` wrappers, curing the vh-stretch layout bug permanently.
- **Z-Index Clipping Issues:** Standardized all UI modals with Tailwind `z-50` bindings and Alpine `<template x-teleport="body">` tags to escape messy container clipping constraints.

## [0.4.0] - 2026-02-22
### Added
- **Floating Glass Aesthetic:** Implemented a global premium frosted-glass aesthetic for both guest and authenticated layouts (`.glass-panel`, `.glass-card`).
- **Collapsible Filter Panels:** Filter forms in Assets and Users views have been moved to clean, collapsible Alpine.js panels with responsive grids, saving vertical screen real-estate.
- **Translucent Interactive States:** Hover, focus, and active item indicators across dropdowns and menus now use glass-friendly translucent backgrounds (`bg-gray-500/10` or dynamic `bg-accent/10`).

### Changed
- Refactored `layout/app.blade.php`: Detached the navbar, header, and main content into floating `z`-layered panels with uniform gaps and a constrained `max-w-7xl` centered wrapper.
- Refactored `layout/guest.blade.php`: Swapped the split-screen design for a full-screen background overlay with a centered floating glass card, including an independently floating logo.
- Enhanced Responsive Layouts: Adjusted mobile vertical/horizontal padding across 25+ inner views.
- Converted the mobile property switcher into a sleek collapsible dropdown to keep the mobile menu clean.

### Fixed
- Fixed mobile browser background rendering issues (iOS Safari) by replacing `bg-fixed` with a `fixed inset-0` div.
- Addressed horizontal overflow clipping caused by data tables stretching beyond their wrappers on mobile devices.

## [0.3.0] - 2026-02-22
### Added
- **Property Isolation Architecture:** Core tables (Assets, Categories, Departments, Roles, Users) are now fully property-scoped (e.g., Novotel YIA vs. Ibis YIA).
- **Super Admin Role:** Global oversight role `is_super_admin` allowing a user to see and switch between all active properties seamlessly.
- **Jobs & Ticketing:** New `Job` model mapping maintenance tasks and comments directly to assets.
- **Asset Attachments:** Introduced polymorphic attachments table to upload documentation, images, and manual files to specific assets, including batch `.zip` downloading.
- **Session-Based Active Property:** Global routing middleware `check.property` to isolate views and database queries to the user's active property context automatically.

### Changed
- Replaced MySQL dependency with MariaDB `10.11` for more robust container compatibility and efficiency.
- Re-styled and expanded standard user creation flow, moving property IDs naturally through Alpine components and Blade templates.
- Enforced `php-imagick` for direct backend QR payload generation (fixing unrendered payloads).
- Swapped default initial seeder placeholders from Novotel/Ibis to Novotel YIA/Ibis YIA.

### Fixed
- Missing `properties.index` and subset routes that had dropped during mid-merge conflicts.
- Refactored Role and User access logic completely, guaranteeing standard admins cannot escape property constraints.

## [0.2.0] - 2026-02-19
### Added
- Integrated continuous integration schema: `semantic-release` workflow for automated release notes.
- Editor column on asset table, showing the latest user who edited the asset.
- Editor info on dashboard and asset details.

### Changed
- Rearranged backend of the dashboard, now uses last updated instead of last created.
- Make email field nullable.
- Removed total asset value in dashboard stats to improve summary clarity.

### Fixed
- Excel export functionality restored and fixed formatting issues.
- Codebase lines cleaned up, resolving previous spacing quirks.
- Hide administrational menus for non-admins in mobile viewport format.

## [0.1.1] - 2025-10-18
### Added
- Ability to view all departments for department with code "PTLP".
- Project scaffold and basic Laravel application structure (routes, controllers, models).
- Initial CHANGELOG setup and tracking.

### Fixed
- Fixing bug on changing department while editing specific assets.

## [0.1.0] - INITIAL
### Added
- Authentication scaffolding, user management, and default Blade views.
- Core database migrations and initial seeders.
- Basic tests infrastructure and composer/npm setups.
- Initial MIT License.

### Changed
- Initial dependency versions pinned for reproducible installs.

### Fixed
- Initial bug fixes discovered during early manual testing.

### Security
- Input validation added for public endpoints.

----------------------------------------------------------------
## Authors
- Contributors: Bara Rifki Annajib(Bara-BSI)

## License
- Project license (see LICENSE file)
