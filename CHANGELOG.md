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
