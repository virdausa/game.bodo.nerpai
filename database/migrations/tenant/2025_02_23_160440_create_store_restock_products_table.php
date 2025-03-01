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
        Schema::create('store_restock_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_restock_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();

            $table->integer('quantity')->default(1);
            $table->decimal('cost_per_unit', 15, 2)->default(0);
            $table->decimal('total_cost', 25, 2)->default(0);
            $table->string('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_restock_products');
    }
};
