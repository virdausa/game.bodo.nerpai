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
        Schema::create('fixed_assets', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Unique asset code
            $table->string('asset_code')->unique();

            // Foreign keys with ON DELETE SET NULL 
            // $table->foreignId('asset_type_id')->nullable()->constrained('asset_types', 'id')->onDelete('set null');
            // $table->foreignId('asset_location_id')->nullable()->constrained('asset_locations', 'id')->onDelete('set null');

            // Attributes
            $table->string('name');
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_value', 20, 2)->nullable()->default(0);
            $table->decimal('useful_life', 20, 2)->nullable();
            $table->string('depreciation_method')->nullable();
            $table->decimal('residual_value', 20, 2)->nullable();
            $table->decimal('current_value', 20, 2)->nullable();
            $table->string('status')->default('active');

            // Timestamps and Soft Deletes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};
