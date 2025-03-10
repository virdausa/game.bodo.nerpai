<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // Foreign Keys
            $table->foreignId('payment_id')->constrained();
            $table->string('invoice_type')->nullable()->default('INV');             // POI, SOI, INV
            $table->unsignedBigInteger('invoice_id')->nullable();

            // Attributes
            $table->decimal('amount', 30, 2)->default(0);
            $table->decimal('balance', 30, 2)->default(0);
            $table->string('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_details');
    }
};
