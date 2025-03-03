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
        // Schema::table('outbounds', function (Blueprint $table) {
        //     $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('set null');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('outbounds', function (Blueprint $table) {
        //     $table->dropForeign('outbounds_shipment_id_foreign');
        //     $table->dropColumn('shipment_id');
        // });
    }
};
