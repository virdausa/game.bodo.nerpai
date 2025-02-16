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

            // Foreign key
            $table->foreignId('created_by')->nullable()->constrained('employees', 'id');

            // Columns
            $table->string('no')->unique();
            $table->date('date');
            $table->string('type')->default("MNL");
            $table->text('description')->nullable();
            $table->decimal('total', 20, 2)->default(0);

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
