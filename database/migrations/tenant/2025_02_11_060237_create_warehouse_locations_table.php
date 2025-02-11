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
        Schema::create('warehouse_locations', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign key
            $table->foreignId('warehouse_id')->constrained();

            // Columns
            $table->string('room');
            $table->string('rack');
            $table->string('notes')->nullable();

            // Timestamps (optional, not in ERD but commonly used)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_locations');
    }
};
