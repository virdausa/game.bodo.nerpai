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
        Schema::create('journal_entries_details', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign key
            $table->foreignId('journal_entry_id')->nullable()->constrained('journal_entries', 'id');
            $table->foreignId('account_id')->nullable()->constrained('accounts', 'id');

            // Columns
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->text('notes')->nullable();

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
        Schema::dropIfExists('journal_entries_details');
    }
};
