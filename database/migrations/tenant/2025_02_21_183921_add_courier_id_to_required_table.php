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
            // Primary keys
            $table->foreignId('courier_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->foreignId('courier_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_courier_id_foreign');
            $table->dropColumn('courier_id');
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign('shipments_courier_id_foreign');
            $table->dropColumn('courier_id');
        });
    }
};
