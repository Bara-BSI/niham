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
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'can_create')) {
                $table->dropColumn(['can_create', 'can_read', 'can_update', 'can_delete']);
            }
            if (!Schema::hasColumn('roles', 'perm_assets')) {
                $table->string('perm_assets')->nullable()->default('no access');
                $table->string('perm_users')->nullable()->default('no access');
                $table->string('perm_categories')->nullable()->default('no access');
                $table->string('perm_departments')->nullable()->default('no access');
                $table->string('perm_roles')->nullable()->default('no access');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'perm_assets')) {
                $table->dropColumn(['perm_assets', 'perm_users', 'perm_categories', 'perm_departments', 'perm_roles']);
            }
            if (!Schema::hasColumn('roles', 'can_create')) {
                $table->boolean('can_create')->default(false);
                $table->boolean('can_read')->default(false);
                $table->boolean('can_update')->default(false);
                $table->boolean('can_delete')->default(false);
            }
        });
    }
};
