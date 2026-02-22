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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        // Add property_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->after('department_id')->constrained('properties')->nullOnDelete();
            $table->boolean('is_super_admin')->default(false)->after('property_id');
        });

        // Add property_id to assets
        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->after('editor')->constrained('properties')->nullOnDelete();
        });

        // Add property_id to departments + change unique constraint
        Schema::table('departments', function (Blueprint $table) {
            $table->dropUnique(['code']); // drop old single-column unique
            $table->foreignId('property_id')->nullable()->after('notes')->constrained('properties')->nullOnDelete();
            $table->unique(['code', 'property_id']); // composite unique per property
        });

        // Add property_id to categories + change unique constraint
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['code']); // drop old single-column unique
            $table->foreignId('property_id')->nullable()->after('notes')->constrained('properties')->nullOnDelete();
            $table->unique(['code', 'property_id']); // composite unique per property
        });

        // Add property_id to roles + change unique constraint
        Schema::table('roles', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->after('can_delete')->constrained('properties')->nullOnDelete();
            $table->dropUnique(['name']); // drop old single-column unique (if exists)
            $table->unique(['name', 'property_id']); // composite unique per property
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['name', 'property_id']);
            $table->dropForeign(['property_id']);
            $table->dropColumn('property_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['code', 'property_id']);
            $table->dropForeign(['property_id']);
            $table->dropColumn('property_id');
            $table->unique(['code']); // restore original
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropUnique(['code', 'property_id']);
            $table->dropForeign(['property_id']);
            $table->dropColumn('property_id');
            $table->unique(['code']); // restore original
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropColumn('property_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropColumn(['property_id', 'is_super_admin']);
        });

        Schema::dropIfExists('properties');
    }
};
