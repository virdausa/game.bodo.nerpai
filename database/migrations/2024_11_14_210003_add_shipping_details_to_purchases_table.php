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
		Schema::table('purchases', function (Blueprint $table) {
			$table->date('shipped_date')->nullable();
			$table->string('expedition')->nullable();
			$table->string('tracking_no')->nullable();
            // $table->enum('status', ['Planned', 'In Transit', 'Received - Pending Verification', 'Quantity Discrepancy', 'Completed', 'Pending Additional Shipment'])->change();
        });
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
};
