<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sale_invoices', function (Blueprint $table) {
            // Primary key
            $table->id();
            $table->string('number')->nullable()->unique();

            // Foreign keys
            $table->foreignId('sale_id')->constrained('sales', 'id')->onDelete('cascade');

            // Attributes
            $table->date('date');
            $table->date('due_date')->nullable();
            $table->decimal('cost_products', 25, 2)->default(0);
            $table->decimal('vat_input', 25, 2)->default(0);
            $table->decimal('cost_packing', 25, 2)->default(0);
            $table->decimal('cost_insurance', 25, 2)->default(0);
            $table->decimal('cost_freight', 25, 2)->default(0);
            $table->decimal('total_amount', 30, 2)->default(0);
            $table->string('status')->default('unconfirmed');
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_invoices');
    }
};
