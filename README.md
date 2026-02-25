<p align="center">
  <img src="https://raw.githubusercontent.com/Bara-BSI/niham/main/public/niham-logo.png" alt="NIHAM Logo" width="150" height="auto" />
</p>

# 🏨 NIHAM (New Integrated Hotel Asset Management)

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)

**NIHAM** is a robust, modern asset management solution designed specifically for the hotel industry. Built with the latest Laravel framework, it streamlines the tracking, maintenance, and lifecycle management of hotel assets across various departments.

---

## ✨ Key Features

### 🤖 Smart Asset Entry (AI OCR)
- **Automated Data Extraction:** Upload an image of an asset's data plate, and the integrated OCR.space API engine will intelligently scan and auto-fill the Serial Number, Brand, and Asset Name instantaneously.

### 🔐 Granular Role Permissions & Executive Oversight
- **String-Based Modular Access:** Control precise capabilities (e.g., `only view`, `can create`, `full access`) individually for Assets, Users, Categories, and Departments.
- **Executive Oversight:** Empower specific departments with global property-wide visibility, entirely abstracting complex hierarchy checks from the application layer.

### 🖼️ Advanced Image Optimization
- **Auto-Compression:** Uploaded asset attachments are silently intercepted and compressed utilizing Intervention Image v3 to maximize visual clarity while minimizing server storage footprint.

### 📊 Interactive Dashboard
- Real-time statistics on total asset value, status distribution, and departmental breakdowns.
- Quick view of recent asset activities.

### 🏷️ Asset Lifecycle Management
- **Complete CRUD:** Create, Read, Update, and Delete assets easily.
- **Detailed Tracking:** Monitor asset status (In Service, Out of Service, Disposed), warranty expiration, purchase costs, and vendors.
- **Attachments:** Upload and manage images or documents for each asset.

### 🔍 QR Code Integration
- **Generate QR Codes:** Automatically generate unique QR codes for every asset (powered by `php-imagick`).
- **Scan & Verify:** Built-in scanner to quickly retrieve asset details via mobile devices.
- **Public/Private Views:** Secure public resolving of asset information for quick checks.

### 🎨 Dynamic Property Branding & UI Overhauls
- **Floating Glass Aesthetic:** Both guest and authenticated layouts utilize a premium modern aesthetic, featuring `.glass-panel` and `.glass-card` styling with backdrop blurring floating over dynamic, screen-spanning global backgrounds.
- **Customized UI per Property:** Each property can set its own custom `logo`, `accent_color`, and `background_image`. The authenticated context dynamically injects these properties natively via CSS variables and blade-rendered style tags.
- **Completely Distinct Guest Flow:** The login page is uncoupled from the standard Breeze gray-cards, using a stunning full-screen translucent background and a centered floating glass card featuring an independently floating global NIHAM identity logo.
- **Dynamic Property Switching:** Super Admins experience on-the-fly theme and data-context switching when swapping between properties via the sleek collapsible context menus.

### 🛡️ Role & Property-Based Access Control (RBAC)
- **Global Tenancy Scopes (Auto-Filtering):** Standard users are natively isolated to their assigned `property_id` across all queries using a custom `BelongsToProperty` global scope trait.
- **Multi-Property Management:** Create and manage distinct databases of assets for entirely isolated hotel locations (e.g., Novotel YIA vs. Ibis YIA).
- **Super Admin Oversight:** Global users bypass the tenancy scope by default (viewing ALL properties at once seamlessly) or can hone in on a specific property context dynamically.
- **Department Isolation:** Normal users only access assets within their specific department.
- **Executive Oversight:** Special roles (EXE/PTLP) for property-wide visibility across all departments.
- **User Roles:** Granular permissions for Admins, Staff, and Viewers within their assigned properties.

### 🛠️ Work Logs & Maintenance
- **Job Tickets:** Create maintenance and service jobs tied directly to hardware assets.
- **Commenting System:** Log internal notes on asset tickets during servicing.

### 📦 Data Management
- **Excel Export:** Download comprehensive asset reports.
- **Backup & Restore:** Integrated tools for database and file backups.

---

## 🚀 Getting Started

Follow these steps to set up the project locally.

### Prerequisites
- PHP 8.2+
- `php-imagick` extension (Required for QR Code generation)
- Composer
- Node.js 22.x & NPM
- MySQL or MariaDB

### Installation

1.  **Clone the repository**
    ```bash
    git clone https://github.com/Bara-BSI/niham.git
    cd niham
    ```

2.  **Install PHP Dependencies**
    ```bash
    composer install
    ```

3.  **Install Frontend Dependencies**
    ```bash
    npm install
    ```

4.  **Environment Configuration**
    Copy the example environment file and configure your database credentials.
    ```bash
    cp .env.example .env
    ```
    *Update `.env` with your database details (DB_DATABASE, DB_USERNAME, DB_PASSWORD).*

5.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

6.  **Run Migrations & Seeders**
    Set up the database structure and default data.
    ```bash
    php artisan migrate
    # Optional: Seed dummy data
    # php artisan db:seed
    ```

7.  **Link Storage**
    Ensure public access to uploaded files.
    ```bash
    php artisan storage:link
    ```

8.  **Start the Development Server**
    You need two terminals:
    ```bash
    # Terminal 1 (Backend)
    php artisan serve
    
    # Terminal 2 (Frontend)
    npm run dev
    ```

---

## 🛠️ Technology Stack

- **Backend:** Laravel 11/12
- **Frontend:** Blade Templates, Alpine.js, TailwindCSS
- **Database:** MySQL / MariaDB (Supports SQLite for testing)
- **Tools:** Vite, PHPUnit, Pest

---

## 📄 License

The NIHAM project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
