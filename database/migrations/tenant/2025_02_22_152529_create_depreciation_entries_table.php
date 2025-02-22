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
        Schema::create('depreciation_entries', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('fixed_asset_id')->nullable()->constrained('fixed_assets', 'id');
            $table->foreignId('journal_entry_id')->nullable()->constrained('journal_entries', 'id');

            // Attributes
            $table->date('depreciation_date')->nullable();
            $table->decimal('depreciation_amount', 20, 2)->nullable()->default(0);
            $table->decimal('accumulated_depreciation', 20, 2)->nullable();
            $table->string('depreciation_method')->nullable();
            $table->decimal('book_value', 20, 2)->nullable();
            $table->text('notes')->nullable();

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
        Schema::dropIfExists('depreciation_entries');
    }
};
