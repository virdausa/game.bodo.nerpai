<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('store_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('store');
            $table->timestamps();
        });

        Schema::create('store_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('store');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_role_has_permissions', function (Blueprint $table) {
            $table->foreignId('store_role_id')->constrained('store_roles')->onDelete('cascade');
            $table->foreignId('store_permission_id')->constrained('store_permissions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_role_has_permissions');
        Schema::dropIfExists('store_permissions');
        Schema::dropIfExists('store_roles');
    }
};
