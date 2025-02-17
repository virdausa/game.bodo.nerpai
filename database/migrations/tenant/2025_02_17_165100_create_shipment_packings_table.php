<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('shipment_packings', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('shipment_id')->constrained('shipments', 'id');
            $table->foreignId('employee_id')->constrained('employees', 'id');

            // Attributes
            $table->integer('quantity')->default(1);
            $table->decimal('weight', 10, 2);
            $table->decimal('volume', 10, 2);
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipment_packings');
    }
};
