# New Integrated Hotel Asset Management (NIHAM) System

A Laravel 12 + Breeze + Tailwind project for managing hotel assets.

## Features
- Asset CRUD with image attachments
- Department & Role based adjustable access control
- Executive role with global visibility
- Backup & restore (DB + attachments)
- Dashboard with asset stats

## Setup
```bash
git clone https://github.com/Bara-BSI/niham.git
cd niham
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
