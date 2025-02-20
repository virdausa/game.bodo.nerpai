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
            // $table->foreignId('invoice_id')->constrained();
            $table->foreignId('payment_id')->constrained();

            // Attributes
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('notes');

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
