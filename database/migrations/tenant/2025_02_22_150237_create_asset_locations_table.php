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
        Schema::create('asset_locations', function (Blueprint $table) {
            // Primary key
            $table->bigIncrements('id');

            // Attributes
            $table->string('name')->nullable(false);
            $table->string('type')->nullable();
            $table->text('description')->nullable();

            // Foreign key with self-referencing relationship
            $table->foreignId('parent_id')->nullable()->constrained('asset_locations', 'id')->onDelete('set null');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_locations');
    }
};
