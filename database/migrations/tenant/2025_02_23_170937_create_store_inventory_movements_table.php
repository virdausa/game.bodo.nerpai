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
        Schema::create('store_inventory_movements', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('store_id')->constrained('stores', 'id')->onDelete('cascade');
            $table->foreignId('store_product_id')->constrained('store_products', 'id')->onDelete('cascade');
            $table->foreignId('store_location_id')->nullable()->constrained('store_locations', 'id')->onDelete('restrict');

            $table->string('source_type')->nullable();                  // INB, POS, MOVE, SOP
            $table->unsignedBigInteger('source_id')->nullable();

            // Attributes
            $table->integer('quantity')->default(0);
            $table->decimal('cost_per_unit', 20, 2)->default(0);
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
        Schema::dropIfExists('store_inventory_movements');
    }
};
