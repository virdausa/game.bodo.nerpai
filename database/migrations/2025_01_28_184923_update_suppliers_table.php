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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->renameColumn('location', 'address');
            $table->json("address")->change()->nullable();
            $table->renameColumn('contact_info', 'email');
            $table->string("phone_number")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->renameColumn('email', 'contact_info');
            $table->string("address")->change()->nullable();
            $table->renameColumn('address', 'location');
        });
    }
};
