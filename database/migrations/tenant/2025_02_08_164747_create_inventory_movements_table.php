<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // Foreign Keys
            $table->foreignId('product_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();

            // Columns
            $table->integer('quantity');
            $table->string('transaction_type'); // Possible values: 'PO', 'SO', 'ADJUST', 'MOVE'
            $table->unsignedBigInteger('transaction_id')->nullable();
            
            $table->text('notes')->nullable();
            $table->dateTime('time');

            // Timestamps (optional, not in schema but commonly used)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
