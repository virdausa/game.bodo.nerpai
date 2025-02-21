<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('couriers', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Attributes
            $table->string('code')->unique();
            $table->string('name');
            $table->string('contact_info');
            $table->string('website');
            $table->string('status')->default('active');
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('couriers');
    }
};
