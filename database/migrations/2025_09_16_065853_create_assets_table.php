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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('tag');
            $table->string('name');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('in_service'); // in_service, out_of_service, disposed
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->string('vendor')->nullable();
            $table->text('desc')->nullable(); // deskripsi barang
            $table->softDeletes(); // timestamp untuk delete
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
