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
        Schema::create('asset_disposals', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('fixed_asset_id')->constrained('fixed_assets', 'id')->nullable();
            $table->foreignId('journal_entry_id')->nullable()->constrained('journal_entries', 'id')->nullable();
            // $table->foreignId('vendor_id')->nullable()->constrained('vendors', 'id');

            // Attributes
            $table->date('disposal_date')->nullable();
            $table->decimal('disposal_value', 20, 2)->nullable();
            $table->decimal('book_value', 20, 2)->nullable();
            $table->decimal('gain_or_loss', 20, 2)->nullable();
            $table->string('status')->default('Planned')->nullable();
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
        Schema::dropIfExists('asset_disposals');
    }
};
