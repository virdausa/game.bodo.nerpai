<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable()->unique();

            // Foreign Keys
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();

            // Attributes
            $table->date('date');
            $table->string('type')->nullable()->default('INV');               // AP, AR, INV
            $table->date('payment_date')->nullable();
            $table->decimal('total_amount', 30, 2)->default(0);
            $table->decimal('balance', 30, 2)->default(0);
            $table->string('payment_method')->nullable()->default('CASH');
            $table->string('status')->default('PENDING');                   // Pending, Cancelled, Process, Paid, Confirmed, Completed
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
