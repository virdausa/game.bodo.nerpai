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
        Schema::create('shipments', function (Blueprint $table) {
            // Primary key
            $table->id();
            $table->string('shipment_number')->nullable()->unique();

            // Polymorphic shipper (WH/SUP/CUST)
            $table->string('shipper_type');  // 'WH', 'SUP', 'CUST'
            $table->unsignedBigInteger('shipper_id');

            // Polymorphic consignee (WH/SUP/CUST)
            $table->string('consignee_type'); // 'WH', 'SUP', 'CUST'
            $table->unsignedBigInteger('consignee_id');

            // Addresses
            $table->json('origin_address');
            $table->json('destination_address');

            // Transaction details (PO/SO/MOVE)
            $table->string('transaction_type')->default('MOVE'); // 'PO', 'SO', 'MOVE'
            $table->unsignedBigInteger('transaction_id')->nullable();

            // Shipment details
            $table->date('ship_date');
            $table->date('delivery_date')->nullable();
            $table->string('tracking_number')->nullable();
            $table->decimal('shipping_fee', 15, 2)->default(0);
            $table->string('payment_rules')->nullable(); // COD/etc
            $table->text('notes')->nullable();
            $table->string('status')->default('SHIP_IN_TRANSIT');

            // Packing Details
            $table->integer('packing_quantity')->default(1);

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
        Schema::dropIfExists('shipments');
    }
};
