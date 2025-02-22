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
        Schema::table('fixed_assets', function (Blueprint $table) {
            // Foreign keys
            $table->foreignId('asset_type_id')->constrained('asset_types', 'id');
            $table->foreignId('asset_location_id')->constrained('asset_locations', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fixed_assets', function (Blueprint $table) {
            $table->dropForeign('fixed_asset_asset_type_id_foreign');
            $table->dropForeign('fixed_asset_asset_location_id_foreign');
            $table->dropColumn('asset_type_id');
            $table->dropColumn('asset_location_id');
        });
    }
};
