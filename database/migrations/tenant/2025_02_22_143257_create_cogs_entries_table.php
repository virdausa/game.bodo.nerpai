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
        Schema::create('cogs_entries', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('product_id')->constrained('products', 'id');
            
            $table->string('source_type')->nullable();              // Possible values: 'SO', 'POS', 'ADJUST', 'SOP'
            $table->unsignedBigInteger('source_id')->nullable();

            // Attributes
            $table->integer('quantity');
            $table->decimal('cost_per_unit', 20, 2)->nullable();
            $table->decimal('total_cost', 25, 2)->nullable();
            $table->date('date')->nullable();
            $table->text('notes')->nullable();

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
        Schema::dropIfExists('cogs_entries');
    }
};
