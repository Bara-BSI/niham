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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_department')->default(true)->after('is_super_admin');
            $table->boolean('notify_all_properties')->default(false)->after('notify_department');
            $table->boolean('notify_email')->default(false)->after('notify_all_properties');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notify_department', 'notify_all_properties', 'notify_email']);
        });
    }
};
