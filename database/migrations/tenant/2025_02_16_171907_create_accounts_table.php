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
        Schema::create('accounts', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign key
            $table->foreignId('parent_id')->nullable()->constrained('accounts', 'id');

            // Columns
            $table->string('code')->unique();
            $table->string('name');
            $table->string('status');
            $table->decimal('balance', 10, 2)->default(0);

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
        Schema::dropIfExists('accounts');
    }
};
