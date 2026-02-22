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
        Schema::table('properties', function (Blueprint $table) {
            $table->string('logo_path')->nullable();
            $table->string('background_image_path')->nullable();
            $table->string('accent_color', 7)->default('#4f46e5'); // Default indigo-600
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'background_image_path', 'accent_color']);
        });
    }
};
