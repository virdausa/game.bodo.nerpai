<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payables', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('purchase_invoice_id')->constrained('purchase_invoices', 'id');
            $table->foreignId('supplier_id')->constrained('suppliers', 'id');

            // Attributes
            $table->decimal('total_amount', 25, 2)->default(0);
            $table->decimal('balance', 25, 2)->default(0);
            $table->string('status')->default('unpaid');
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payables');
    }
};
