<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('receivables', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('sales_invoice_id')->constrained('sale_invoices', 'id');
            $table->foreignId('customer_id')->constrained('customers', 'id');

            // Attributes
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('balance', 12, 2);
            $table->string('status')->default('unpaid');
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('receivables');
    }
};
