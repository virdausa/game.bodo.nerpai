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
        Schema::create('outbounds', function (Blueprint $table) {
            // Primary key
            $table->id();
            $table->string('number')->nullable()->unique();

            // Foreign keys
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('employee_id')->constrained();

            $table->string('source_type')->nullable();          // ITF, SO
            $table->unsignedBigInteger('source_id')->nullable();
            
            // Columns
            $table->date('date');
            $table->string('status')->default('OUTB_REQUEST');
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
        Schema::dropIfExists('outbounds');
    }
};
