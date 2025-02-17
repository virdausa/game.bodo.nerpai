<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('shipment_confirmation_products', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // Foreign Keys
            $table->foreignId('confirmation_id')->constrained('shipment_confirmations');
            $table->foreignId('product_id')->constrained();

            // Attributes
            $table->integer('quantity');
            $table->string('condition');
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipment_confirmation_products');
    }
};
