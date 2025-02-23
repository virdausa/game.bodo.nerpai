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
        Schema::create('store_pos_products', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('store_pos_id')->nullable()->constrained('store_pos', 'id')->onDelete('set null');
            $table->foreignId('store_product_id')->nullable()->constrained('store_products', 'id')->onDelete('set null');

            // Attributes
            $table->integer('quantity');
            $table->decimal('price', 20, 2);
            $table->decimal('subtotal', 20, 2);
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
        Schema::dropIfExists('store_pos_products');
    }
};
