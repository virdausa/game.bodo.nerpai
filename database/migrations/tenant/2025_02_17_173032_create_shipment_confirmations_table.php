<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('shipment_confirmations', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('shipment_id')->constrained('shipments', 'id');
            $table->foreignId('employee_id')->constrained('employees', 'id');

            // Polymorphic consignee (customer/employee)
            $table->string('consignee_type')->nullable();                   // 'Customer', 'Employee'
            $table->unsignedBigInteger('consignee_id')->nullable();

            // Confirmation details
            $table->string('consignee_signature')->nullable();
            $table->timestamp('received_time')->nullable();
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipment_confirmations');
    }
};
