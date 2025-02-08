<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_products', function (Blueprint $table) {
            // Primary Key
            $table->id('id');

            // Foreign Keys
            $table->foreignId('sale_id')->constrained();
            $table->foreignId('product_id')->constrained();

            // Columns
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->string('notes')->nullable();

            // Timestamps (optional, not in schema but commonly used)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_products');
    }
};
