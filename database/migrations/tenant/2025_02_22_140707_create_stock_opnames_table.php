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
        Schema::create('stock_opnames', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('product_id')->constrained('products', 'id');
            $table->foreignId('warehouse_id')->constrained('warehouses', 'id');
            $table->foreignId('warehouse_location_id')->constrained('warehouse_locations', 'id');

            // Attributes
            $table->integer('system_quantity');
            $table->integer('physical_quantity');
            $table->integer('adjustment_quantity')->nullable()->default(0);
            $table->decimal('adjustment_value', 10, 2)->nullable()->default(0);
            $table->text('notes')->nullable();
            $table->date('date');

            // Foreign Keys
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
