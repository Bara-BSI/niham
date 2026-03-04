<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Consolidated Niham application schema for PostgreSQL.
 *
 * Key PostgreSQL-specific design decisions:
 * - All entity PKs use  bigIncrements (id) + native uuid column for public routing.
 * - JSON columns use jsonb for binary indexing & faster querying.
 * - Compound indexes on (property_id, <fk>) for tenant-scoped lookups via PropertyScope.
 * - Strict FK constraints with explicit ON DELETE behaviour.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Properties (must be first – all others reference it) ──
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('address')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('background_image_path')->nullable();
            $table->string('accent_color', 7)->default('#4f46e5');
            $table->timestamps();
        });

        // ── Roles ──────────────────────────────────────────────────
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('perm_assets')->default('view only');
            $table->string('perm_users')->default('no access');
            $table->string('perm_categories')->default('no access');
            $table->string('perm_departments')->default('no access');
            $table->string('perm_roles')->default('no access');
            $table->timestamps();

            // FK: property_id
            $table->foreignId('property_id')
                ->nullable()
                ->constrained('properties')
                ->nullOnDelete();

            // Tenant-scoped unique constraint
            $table->unique(['name', 'property_id']);
            $table->index('property_id');
        });

        // ── Departments ────────────────────────────────────────────
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('code');
            $table->text('notes')->nullable();
            $table->boolean('is_executive_oversight')->default(false);
            $table->timestamps();

            // FK: property_id
            $table->foreignId('property_id')
                ->nullable()
                ->constrained('properties')
                ->nullOnDelete();

            // Tenant-scoped unique constraint
            $table->unique(['code', 'property_id']);
            $table->index('property_id');
        });

        // ── Extend users table with application columns ────────────
        Schema::table('users', function (Blueprint $table) {
            // FK: role_id
            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles')
                ->nullOnDelete();

            // FK: department_id
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            // FK: property_id
            $table->foreignId('property_id')
                ->nullable()
                ->constrained('properties')
                ->nullOnDelete();

            $table->boolean('is_super_admin')->default(false);

            // Notification preferences
            $table->boolean('notify_department')->default(true);
            $table->boolean('notify_all_properties')->default(false);
            $table->boolean('notify_email')->default(false);
            $table->string('email_frequency')->default('immediate');

            // Compound index for tenant-scoped user lookups
            $table->index(['property_id', 'role_id']);
            $table->index(['property_id', 'department_id']);
        });

        // ── Categories ─────────────────────────────────────────────
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('code');
            $table->text('notes')->nullable();
            $table->timestamps();

            // FK: property_id
            $table->foreignId('property_id')
                ->nullable()
                ->constrained('properties')
                ->nullOnDelete();

            // Tenant-scoped unique constraint
            $table->unique(['code', 'property_id']);
            $table->index('property_id');
        });

        // ── Assets ─────────────────────────────────────────────────
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('tag');
            $table->string('name');
            $table->string('status')->default('in_service');
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_date')->nullable();
            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->string('vendor')->nullable();
            $table->text('desc')->nullable();
            $table->string('remarks', 120)->nullable();
            $table->softDeletes();
            $table->timestamps();

            // FK: category_id (required, cascade on delete)
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            // FK: department_id (optional)
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            // FK: editor (user who last edited)
            $table->foreignId('editor')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // FK: property_id
            $table->foreignId('property_id')
                ->nullable()
                ->constrained('properties')
                ->nullOnDelete();

            // Compound indexes for tenant-scoped queries
            $table->index(['property_id', 'category_id']);
            $table->index(['property_id', 'department_id']);
            $table->index(['property_id', 'status']);
            $table->index('editor');
        });

        // ── Attachments ────────────────────────────────────────────
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('type')->nullable();
            $table->timestamps();

            // FK: asset_id (cascade on delete)
            $table->foreignId('asset_id')
                ->unique()
                ->constrained('assets')
                ->cascadeOnDelete();
        });

        // ── Asset Histories ────────────────────────────────────────
        Schema::create('asset_histories', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->jsonb('original')->nullable();
            $table->jsonb('changes')->nullable();
            $table->timestamps();

            // FK: asset_id (cascade on delete)
            $table->foreignId('asset_id')
                ->constrained('assets')
                ->cascadeOnDelete();

            // FK: user_id (set null on delete)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Index for per-asset history lookups
            $table->index('asset_id');
            $table->index('user_id');
        });

        // ── Notifications ──────────────────────────────────────────
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('asset_histories');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('categories');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['property_id']);
            $table->dropColumn([
                'role_id', 'department_id', 'property_id',
                'is_super_admin',
                'notify_department', 'notify_all_properties',
                'notify_email', 'email_frequency',
            ]);
        });

        Schema::dropIfExists('departments');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('properties');
    }
};
