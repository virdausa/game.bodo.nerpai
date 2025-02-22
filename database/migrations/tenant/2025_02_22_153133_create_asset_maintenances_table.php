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
        Schema::create('asset_maintenances', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('fixed_asset_id')->nullable()->constrained('fixed_assets', 'id');
            $table->foreignId('journal_entry_id')->nullable()->constrained('journal_entries', 'id');
            // $table->foreignId('vendor_id')->nullable()->constrained('vendors', 'id');

            // Attributes
            $table->date('maintenance_date')->nullable();
            $table->string('description')->nullable();
            $table->decimal('cost', 20, 2)->nullable()->default(0);
            $table->string('status')->nullable()->default('Planned');
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
        Schema::dropIfExists('asset_maintenances');
    }
};
