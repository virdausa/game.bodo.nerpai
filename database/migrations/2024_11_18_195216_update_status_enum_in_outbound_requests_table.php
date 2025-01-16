<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
	{
		Schema::table('outbound_requests', function (Blueprint $table) {
			// $table->enum('status', ['Requested', 'Pending Confirmation', 'Packing & Shipping', 'In Transit', 'Customer Complaint', 'Ready to Complete', 'Completed'])->default('Requested')->change();
		});
	}

	public function down()
	{
		Schema::table('outbound_requests', function (Blueprint $table) {
			$table->enum('status', ['Requested', 'Pending Confirmation', 'Packing & Shipping', 'In Transit', 'Customer Complaint'])->default('Requested')->change();
		});
	}

};
