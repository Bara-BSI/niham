<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use App\Models\Property;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Properties ────────────────────────────────────────────
        $novotel = Property::updateOrCreate(
            ['code' => 'NVT'],
            ['name' => 'Novotel YIA', 'address' => 'Yogyakarta International Airport, Kulon Progo']
        );

        $ibis = Property::updateOrCreate(
            ['code' => 'IBIS'],
            ['name' => 'Ibis YIA', 'address' => 'Yogyakarta International Airport, Kulon Progo']
        );

        // ── Roles (per property) ──────────────────────────────────
        // Novotel roles
        $adminRoleNvt = Role::updateOrCreate(
            ['name' => 'admin', 'property_id' => $novotel->id],
            ['perm_assets' => 'full access', 'perm_users' => 'full access', 'perm_categories' => 'full access', 'perm_departments' => 'full access', 'perm_roles' => 'full access']
        );
        $staffRoleNvt = Role::updateOrCreate(
            ['name' => 'staff', 'property_id' => $novotel->id],
            ['perm_assets' => 'create', 'perm_users' => 'view only', 'perm_categories' => 'view only', 'perm_departments' => 'view only', 'perm_roles' => 'no access']
        );
        $managerRoleNvt = Role::updateOrCreate(
            ['name' => 'manager', 'property_id' => $novotel->id],
            ['perm_assets' => 'create & update', 'perm_users' => 'view only', 'perm_categories' => 'view only', 'perm_departments' => 'view only', 'perm_roles' => 'no access']
        );

        // Ibis roles
        $adminRoleIbis = Role::updateOrCreate(
            ['name' => 'admin', 'property_id' => $ibis->id],
            ['perm_assets' => 'full access', 'perm_users' => 'full access', 'perm_categories' => 'full access', 'perm_departments' => 'full access', 'perm_roles' => 'full access']
        );
        $staffRoleIbis = Role::updateOrCreate(
            ['name' => 'staff', 'property_id' => $ibis->id],
            ['perm_assets' => 'create', 'perm_users' => 'view only', 'perm_categories' => 'view only', 'perm_departments' => 'view only', 'perm_roles' => 'no access']
        );
        $managerRoleIbis = Role::updateOrCreate(
            ['name' => 'manager', 'property_id' => $ibis->id],
            ['perm_assets' => 'create & update', 'perm_users' => 'view only', 'perm_categories' => 'view only', 'perm_departments' => 'view only', 'perm_roles' => 'no access']
        );

        // ── Departments (Novotel) ─────────────────────────────────
        $nvtTC = Department::updateOrCreate(
            ['name' => 'TALENT AND CULTURE', 'property_id' => $novotel->id],
            ['code' => 'TC']
        );
        $nvtFB = Department::updateOrCreate(
            ['name' => 'FOOD AND BEVERAGE', 'property_id' => $novotel->id],
            ['code' => 'FB']
        );
        $nvtIT = Department::updateOrCreate(
            ['name' => 'INFORMATION TECHNOLOGY', 'property_id' => $novotel->id],
            ['code' => 'IT']
        );
        $nvtFO = Department::updateOrCreate(
            ['name' => 'FRONT OFFICE', 'property_id' => $novotel->id],
            ['code' => 'FO']
        );
        $nvtEXE = Department::updateOrCreate(
            ['name' => 'EXECUTIVE', 'property_id' => $novotel->id],
            ['code' => 'EXE']
        );
        $nvtHK = Department::updateOrCreate(
            ['name' => 'HOUSEKEEPING', 'property_id' => $novotel->id],
            ['code' => 'HK']
        );
        $nvtENG = Department::updateOrCreate(
            ['name' => 'ENGINEERING', 'property_id' => $novotel->id],
            ['code' => 'ENG']
        );

        // ── Departments (Ibis) ─────────────────────────────────
        $ibisIT = Department::updateOrCreate(
            ['name' => 'INFORMATION TECHNOLOGY', 'property_id' => $ibis->id],
            ['code' => 'IT']
        );
        $ibisFO = Department::updateOrCreate(
            ['name' => 'FRONT OFFICE', 'property_id' => $ibis->id],
            ['code' => 'FO']
        );
        $ibisEXE = Department::updateOrCreate(
            ['name' => 'EXECUTIVE', 'property_id' => $ibis->id],
            ['code' => 'EXE']
        );
        $ibisHK = Department::updateOrCreate(
            ['name' => 'HOUSEKEEPING', 'property_id' => $ibis->id],
            ['code' => 'HK']
        );
        $ibisFB = Department::updateOrCreate(
            ['name' => 'FOOD AND BEVERAGE', 'property_id' => $ibis->id],
            ['code' => 'FB']
        );

        // ── Categories (Novotel) ──────────────────────────────────
        $nvtPC = Category::updateOrCreate(
            ['name' => 'PERSONAL COMPUTER', 'property_id' => $novotel->id],
            ['code' => 'PC']
        );
        $nvtMON = Category::updateOrCreate(
            ['name' => 'MONITOR', 'property_id' => $novotel->id],
            ['code' => 'MON']
        );
        $nvtPRN = Category::updateOrCreate(
            ['name' => 'PRINTER', 'property_id' => $novotel->id],
            ['code' => 'PRN']
        );
        $nvtNET = Category::updateOrCreate(
            ['name' => 'NETWORKING', 'property_id' => $novotel->id],
            ['code' => 'NET']
        );
        $nvtCBL = Category::updateOrCreate(
            ['name' => 'CABLE', 'property_id' => $novotel->id],
            ['code' => 'CBL']
        );
        $nvtTV = Category::updateOrCreate(
            ['name' => 'TELEVISION', 'property_id' => $novotel->id],
            ['code' => 'TV']
        );

        // ── Categories (Ibis) ──────────────────────────────────
        $ibisPC = Category::updateOrCreate(
            ['name' => 'PERSONAL COMPUTER', 'property_id' => $ibis->id],
            ['code' => 'PC']
        );
        $ibisMON = Category::updateOrCreate(
            ['name' => 'MONITOR', 'property_id' => $ibis->id],
            ['code' => 'MON']
        );
        $ibisPRN = Category::updateOrCreate(
            ['name' => 'PRINTER', 'property_id' => $ibis->id],
            ['code' => 'PRN']
        );
        $ibisTV = Category::updateOrCreate(
            ['name' => 'TELEVISION', 'property_id' => $ibis->id],
            ['code' => 'TV']
        );

        // ── Users ─────────────────────────────────────────────────
        // Super Admin
        $superAdmin = User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => bcrypt('superadmin'),
                'role_id' => $adminRoleNvt->id,
                'department_id' => $nvtEXE->id,
                'property_id' => $novotel->id,
                'is_super_admin' => true,
            ]
        );

        // ── Novotel Users ──────────────────────────────────────
        $adminNvt = User::updateOrCreate(
            ['username' => 'admin.nvt'],
            [
                'name' => 'Admin Novotel YIA',
                'email' => 'admin@novotelyia.com',
                'password' => bcrypt('123admin'),
                'role_id' => $adminRoleNvt->id,
                'department_id' => $nvtEXE->id,
                'property_id' => $novotel->id,
                'is_super_admin' => false,
            ]
        );

        $baraNvt = User::updateOrCreate(
            ['username' => 'bara'],
            [
                'name' => 'Bara',
                'email' => 'bara@novotelyia.com',
                'password' => bcrypt('bara'),
                'role_id' => $staffRoleNvt->id,
                'department_id' => $nvtIT->id,
                'property_id' => $novotel->id,
                'is_super_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['username' => 'itman.nvt'],
            [
                'name' => 'IT Manager Novotel YIA',
                'email' => 'itman@novotelyia.com',
                'password' => bcrypt('itman'),
                'role_id' => $managerRoleNvt->id,
                'department_id' => $nvtIT->id,
                'property_id' => $novotel->id,
                'is_super_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['username' => 'chef.nvt'],
            [
                'name' => 'Chef Novotel YIA',
                'email' => 'chef@novotelyia.com',
                'password' => bcrypt('chef'),
                'role_id' => $staffRoleNvt->id,
                'department_id' => $nvtFB->id,
                'property_id' => $novotel->id,
                'is_super_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['username' => 'hk.nvt'],
            [
                'name' => 'Housekeeping Novotel YIA',
                'email' => 'hk@novotelyia.com',
                'password' => bcrypt('hk123'),
                'role_id' => $staffRoleNvt->id,
                'department_id' => $nvtHK->id,
                'property_id' => $novotel->id,
                'is_super_admin' => false,
            ]
        );

        // ── Ibis Users ──────────────────────────────────────
        $adminIbis = User::updateOrCreate(
            ['username' => 'admin.ibis'],
            [
                'name' => 'Admin Ibis YIA',
                'email' => 'admin@ibisyia.com',
                'password' => bcrypt('123admin'),
                'role_id' => $adminRoleIbis->id,
                'department_id' => $ibisEXE->id,
                'property_id' => $ibis->id,
                'is_super_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['username' => 'staff.ibis'],
            [
                'name' => 'Staff IT Ibis YIA',
                'email' => 'staffit@ibisyia.com',
                'password' => bcrypt('staff'),
                'role_id' => $staffRoleIbis->id,
                'department_id' => $ibisIT->id,
                'property_id' => $ibis->id,
                'is_super_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['username' => 'fo.ibis'],
            [
                'name' => 'Front Office Ibis YIA',
                'email' => 'fo@ibisyia.com',
                'password' => bcrypt('fo123'),
                'role_id' => $staffRoleIbis->id,
                'department_id' => $ibisFO->id,
                'property_id' => $ibis->id,
                'is_super_admin' => false,
            ]
        );

        // ── Assets (Novotel) ───────────────────────────────────
        $nvtAssets = [
            ['tag' => 'NVT-PC-001', 'name' => 'PC Front Desk 1',      'category_id' => $nvtPC->id,  'department_id' => $nvtFO->id,  'status' => 'in_service',      'serial_number' => 'DELL-SN-001', 'purchase_cost' => 12000000, 'vendor' => 'Dell Indonesia'],
            ['tag' => 'NVT-PC-002', 'name' => 'PC Front Desk 2',      'category_id' => $nvtPC->id,  'department_id' => $nvtFO->id,  'status' => 'in_service',      'serial_number' => 'DELL-SN-002', 'purchase_cost' => 12000000, 'vendor' => 'Dell Indonesia'],
            ['tag' => 'NVT-PC-003', 'name' => 'Workstation IT',       'category_id' => $nvtPC->id,  'department_id' => $nvtIT->id,  'status' => 'in_service',      'serial_number' => 'LEN-SN-003',  'purchase_cost' => 18000000, 'vendor' => 'Lenovo'],
            ['tag' => 'NVT-PC-004', 'name' => 'PC Executive Office',  'category_id' => $nvtPC->id,  'department_id' => $nvtEXE->id, 'status' => 'in_service',      'serial_number' => 'HP-SN-004',   'purchase_cost' => 15000000, 'vendor' => 'HP Indonesia'],
            ['tag' => 'NVT-MON-001', 'name' => 'Monitor Front Desk 1', 'category_id' => $nvtMON->id, 'department_id' => $nvtFO->id,  'status' => 'in_service',      'serial_number' => 'LG-SN-M01',   'purchase_cost' => 3500000,  'vendor' => 'LG Electronics'],
            ['tag' => 'NVT-MON-002', 'name' => 'Monitor Front Desk 2', 'category_id' => $nvtMON->id, 'department_id' => $nvtFO->id,  'status' => 'in_service',      'serial_number' => 'LG-SN-M02',   'purchase_cost' => 3500000,  'vendor' => 'LG Electronics'],
            ['tag' => 'NVT-MON-003', 'name' => 'Monitor IT Room',      'category_id' => $nvtMON->id, 'department_id' => $nvtIT->id,  'status' => 'in_service',      'serial_number' => 'ASUS-SN-M03', 'purchase_cost' => 5000000,  'vendor' => 'ASUS Indonesia'],
            ['tag' => 'NVT-PRN-001', 'name' => 'Printer Front Office', 'category_id' => $nvtPRN->id, 'department_id' => $nvtFO->id,  'status' => 'in_service',      'serial_number' => 'EP-SN-P01',   'purchase_cost' => 4500000,  'vendor' => 'Epson'],
            ['tag' => 'NVT-PRN-002', 'name' => 'Printer Executive',    'category_id' => $nvtPRN->id, 'department_id' => $nvtEXE->id, 'status' => 'out_of_service',  'serial_number' => 'HP-SN-P02',   'purchase_cost' => 6000000,  'vendor' => 'HP Indonesia'],
            ['tag' => 'NVT-NET-001', 'name' => 'Switch Lobby',         'category_id' => $nvtNET->id, 'department_id' => $nvtIT->id,  'status' => 'in_service',      'serial_number' => 'CISCO-001',   'purchase_cost' => 8000000,  'vendor' => 'Cisco'],
            ['tag' => 'NVT-NET-002', 'name' => 'Access Point Floor 1', 'category_id' => $nvtNET->id, 'department_id' => $nvtIT->id,  'status' => 'in_service',      'serial_number' => 'UBNT-001',    'purchase_cost' => 2500000,  'vendor' => 'Ubiquiti'],
            ['tag' => 'NVT-NET-003', 'name' => 'Access Point Floor 2', 'category_id' => $nvtNET->id, 'department_id' => $nvtIT->id,  'status' => 'disposed',        'serial_number' => 'UBNT-002',    'purchase_cost' => 2500000,  'vendor' => 'Ubiquiti'],
            ['tag' => 'NVT-TV-001', 'name' => 'TV Lobby',             'category_id' => $nvtTV->id,  'department_id' => $nvtFO->id,  'status' => 'in_service',      'serial_number' => 'SAM-TV-001',  'purchase_cost' => 7500000,  'vendor' => 'Samsung'],
            ['tag' => 'NVT-TV-002', 'name' => 'TV Restaurant',        'category_id' => $nvtTV->id,  'department_id' => $nvtFB->id,  'status' => 'in_service',      'serial_number' => 'SAM-TV-002',  'purchase_cost' => 7500000,  'vendor' => 'Samsung'],
            ['tag' => 'NVT-CBL-001', 'name' => 'UTP Cat6 Box (300m)',  'category_id' => $nvtCBL->id, 'department_id' => $nvtIT->id,  'status' => 'in_service',      'serial_number' => null,          'purchase_cost' => 1200000,  'vendor' => 'Belden'],
        ];

        foreach ($nvtAssets as $a) {
            Asset::updateOrCreate(
                ['tag' => $a['tag'], 'property_id' => $novotel->id],
                array_merge($a, ['property_id' => $novotel->id, 'editor' => $adminNvt->id])
            );
        }

        // ── Assets (Ibis) ───────────────────────────────────
        $ibisAssets = [
            ['tag' => 'IBIS-PC-001', 'name' => 'PC Reception 1',       'category_id' => $ibisPC->id,  'department_id' => $ibisFO->id,  'status' => 'in_service',     'serial_number' => 'DELL-I-001', 'purchase_cost' => 10000000, 'vendor' => 'Dell Indonesia'],
            ['tag' => 'IBIS-PC-002', 'name' => 'PC Reception 2',       'category_id' => $ibisPC->id,  'department_id' => $ibisFO->id,  'status' => 'in_service',     'serial_number' => 'DELL-I-002', 'purchase_cost' => 10000000, 'vendor' => 'Dell Indonesia'],
            ['tag' => 'IBIS-PC-003', 'name' => 'PC Back Office',       'category_id' => $ibisPC->id,  'department_id' => $ibisEXE->id, 'status' => 'in_service',     'serial_number' => 'HP-I-003',   'purchase_cost' => 11000000, 'vendor' => 'HP Indonesia'],
            ['tag' => 'IBIS-MON-001', 'name' => 'Monitor Reception 1',  'category_id' => $ibisMON->id, 'department_id' => $ibisFO->id,  'status' => 'in_service',     'serial_number' => 'LG-I-M01',   'purchase_cost' => 3000000,  'vendor' => 'LG Electronics'],
            ['tag' => 'IBIS-MON-002', 'name' => 'Monitor Reception 2',  'category_id' => $ibisMON->id, 'department_id' => $ibisFO->id,  'status' => 'out_of_service', 'serial_number' => 'LG-I-M02',   'purchase_cost' => 3000000,  'vendor' => 'LG Electronics'],
            ['tag' => 'IBIS-PRN-001', 'name' => 'Printer Reception',    'category_id' => $ibisPRN->id, 'department_id' => $ibisFO->id,  'status' => 'in_service',     'serial_number' => 'EP-I-P01',   'purchase_cost' => 3500000,  'vendor' => 'Epson'],
            ['tag' => 'IBIS-TV-001', 'name' => 'TV Lobby Ibis',        'category_id' => $ibisTV->id,  'department_id' => $ibisFO->id,  'status' => 'in_service',     'serial_number' => 'LG-I-TV01',  'purchase_cost' => 5000000,  'vendor' => 'LG Electronics'],
            ['tag' => 'IBIS-TV-002', 'name' => 'TV Cafe Ibis',         'category_id' => $ibisTV->id,  'department_id' => $ibisFB->id,  'status' => 'in_service',     'serial_number' => 'SAM-I-TV02', 'purchase_cost' => 5000000,  'vendor' => 'Samsung'],
        ];

        foreach ($ibisAssets as $a) {
            Asset::updateOrCreate(
                ['tag' => $a['tag'], 'property_id' => $ibis->id],
                array_merge($a, ['property_id' => $ibis->id, 'editor' => $adminIbis->id])
            );
        }
    }
}
