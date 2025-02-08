<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movement', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // Foreign Keys
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('warehouse_id')->constrained('warehouses');

            // Columns
            $table->integer('quantity')->nullable(false);
            $table->string('transaction_type')->nullable(false); // Possible values: 'PO', 'SO', 'ADJUST', 'MOVE'
            $table->text('notes')->nullable();
            $table->dateTime('time')->nullable(false);

            // Timestamps (optional, not in schema but commonly used)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movement');
    }
};
