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
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('employee_id')->constrained();
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('employee_id')->constrained();
        });

        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->foreignId('employee_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_employee_id_foreign');
            $table->dropColumn('employee_id');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign('purchases_employee_id_foreign');
            $table->dropColumn('employee_id');
        });

        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropForeign('inventory_movements_employee_id_foreign');
            $table->dropColumn('employee_id');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign('warehouses_employee_id_foreign');
            $table->dropColumn('employee_id');
        });
    }
};
