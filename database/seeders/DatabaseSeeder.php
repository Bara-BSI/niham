<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
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
         // Roles
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            ['can_create' => true, 'can_read' => true, 'can_update' => true, 'can_delete' => true]
        );

        $staffRole = Role::updateOrCreate(
            ['name' => 'staff'],
            ['can_create' => true, 'can_read' => true, 'can_update' => false, 'can_delete' => false]
        );

        $managerRole = Role::updateOrCreate(
            ['name' => 'manager'],
            ['can_create' => true, 'can_read' => true, 'can_update' => true, 'can_delete' => false]
        );

        Department::insert([
            ['name'=>'TALENT AND CULTURE','code'=>'TC'],
            ['name'=>'FOOD AND BEVERAGE','code'=>'FB'],
            ['name'=>'INFORMATION TECHNOLOGY','code'=>'IT'],
            ['name'=>'FRONT OFFICE','code'=>'FO'],
            ['name'=>'EXECUTIVE','code'=>'EXE']
        ]);

        User::firstOrCreate([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('123admin'),
            'role_id' => Role::where('name','admin')->first()->id,
            'department_id' => Department::where('code','EXE')->first()->id,
        ]);

        User::firstOrCreate([
            'name' => 'Bara',
            'username' => 'bara',
            'email' => 'bara@example.com',
            'password' => bcrypt('bara'),
            'role_id' => Role::where('name','staff')->first()->id,
            'department_id' => Department::where('code','IT')->first()->id,
        ]);

        User::firstOrCreate([
            'name' => 'IT Manager',
            'username' => 'itman',
            'email' => 'itman@example.com',
            'password' => bcrypt('itman'),
            'role_id' => Role::where('name','manager')->first()->id,
            'department_id' => Department::where('code','IT')->first()->id,
        ]);

        Category::insert([
            ['name'=>'PERSONAL COMPUTER','code'=>'PC']
        ]);

        // Asset::factory()->count(50)->create();
    }
}
