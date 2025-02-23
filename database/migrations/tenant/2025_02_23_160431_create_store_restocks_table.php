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
        Schema::create('store_restocks', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('store_id')->nullable()->constrained('stores', 'id')->onDelete('set null');
            $table->foreignId('store_employee_id')->nullable()->constrained('store_employees', 'id');

            // Attributes
            $table->date('restock_date');
            $table->string('status')->default('ST_RES_REQ');
            $table->text('admin_notes')->nullable();
            $table->text('team_notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_restocks');
    }
};
