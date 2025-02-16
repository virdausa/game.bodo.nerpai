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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_amount', 30, 2)->default(0);
            $table->string('status')->default('PO_Planned');
            $table->string('admin_notes')->nullable();
            $table->string('supplier_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('warehouse_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
