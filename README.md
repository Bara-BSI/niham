# 🏨 NIHAM (New Integrated Hotel Asset Management)

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)

**NIHAM** is a robust, modern asset management solution designed specifically for the hotel industry. Built with the latest Laravel framework, it streamlines the tracking, maintenance, and lifecycle management of hotel assets across various departments.

---

## ✨ Key Features

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

### 🛡️ Role & Property-Based Access Control (RBAC)
- **Multi-Property Management:** Create and manage distinct databases of assets for entirely isolated hotel locations (e.g., Novotel YIA vs. Ibis YIA).
- **Super Admin:** Global users that can effortlessly switch contexts between property schemas.
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
