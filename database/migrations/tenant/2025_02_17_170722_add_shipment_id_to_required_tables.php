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
        Schema::table('inbounds', function (Blueprint $table) {
            $table->foreignId('shipment_id')->constrained();
        });

        Schema::table('outbounds', function (Blueprint $table) {
            $table->foreignId('shipment_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inbounds', function (Blueprint $table) {
            $table->dropForeign('inbounds_shipment_id_foreign');
            $table->dropColumn('shipment_id');
        });

        Schema::table('outbounds', function (Blueprint $table) {
            $table->dropForeign('outbounds_shipment_id_foreign');
            $table->dropColumn('shipment_id');
        });
    }
};
