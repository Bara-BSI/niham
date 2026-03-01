<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $map = [
            'only view' => 'view only',
            'view' => 'view only',
            'view, create' => 'create',
            'can create' => 'create',
            'view, update' => 'update',
            'can update' => 'update',
            'can delete' => 'delete',
            'view, create, update' => 'create & update',
            'can create and update' => 'create & update',
            'view, create, delete' => 'create & delete',
            'can create and delete' => 'create & delete',
            'view, update, delete' => 'update & delete',
            'can update and delete' => 'update & delete',
            'view, create, update, delete' => 'full access',
            // Default edge cases already handle 'no access' and 'full access' untouched
        ];

        $columns = ['perm_assets', 'perm_users', 'perm_categories', 'perm_departments', 'perm_roles'];

        foreach ($columns as $column) {
            foreach ($map as $old => $new) {
                \DB::table('roles')->where($column, $old)->update([$column => $new]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversal mapping is lossy, implement basic reverse if absolutely needed,
        // or leave empty for a pure forward migration.
    }
};
