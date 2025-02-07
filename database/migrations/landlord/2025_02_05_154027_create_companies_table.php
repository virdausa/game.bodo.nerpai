<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('address');
            $table->string('database');
            $table->timestamps(); // Otomatis buat created_at & updated_at
            $table->softDeletes(); // Untuk deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

