<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePurchaseStatusEnum extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            // $table->enum('status', ['Planned', 'In Transit', 'Completed', 'Quantity Discrepancy', 'Pending Additional Shipment'])->default('Planned')->change('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->enum('status', ['Planned', 'In Transit', 'Completed', 'Quantity Discrepancy'])->default('Planned')->change();
        });
    }
}
