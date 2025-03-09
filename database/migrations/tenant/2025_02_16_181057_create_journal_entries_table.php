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
        Schema::create('journal_entries', function (Blueprint $table) {
            // Primary key
            $table->id();
            $table->string('number')->nullable()->unique();

            // Foreign key
            $table->foreignId('created_by')->nullable()->constrained('employees', 'id');
            $table->string('source_type')->nullable();                      // POI, SOI, INV                  
            $table->unsignedBigInteger('source_id')->nullable();       

            // Columns
            $table->date('date');
            $table->string('type')->nullable()->default("MNL");                         // MNL, INV
            $table->text('description')->nullable();
            $table->decimal('total', 25, 2)->default(0);

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
        Schema::dropIfExists('journal_entries');
    }
};
