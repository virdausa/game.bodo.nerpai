<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('packing_products', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('packing_id')->constrained('shipment_packings', 'id');
            $table->foreignId('product_id')->constrained('products', 'id');

            // Attributes
            $table->integer('quantity');
            $table->decimal('packing_weight', 10, 2);
            $table->decimal('packing_volume', 10, 2);
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('packing_products');
    }
};
