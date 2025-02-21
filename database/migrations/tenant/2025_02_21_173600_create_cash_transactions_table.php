<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('account_id')->constrained('accounts');
            $table->unsignedBigInteger('source_id');

            // Attributes
            $table->string('transaction_number')->unique();
            $table->date('date');
            $table->string('type');
            $table->string('source');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_transactions');
    }
};
