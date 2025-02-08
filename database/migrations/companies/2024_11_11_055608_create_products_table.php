<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
	{
		Schema::create('products', function (Blueprint $table) {
			$table->id();
            $table->string('sku')->unique();
			$table->string('name');
			$table->decimal('price');
            $table->decimal('weight');
            $table->string('status');
            $table->text('notes')->nullable();
			$table->timestamps();
            $table->softDeletes();
		});
	}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
