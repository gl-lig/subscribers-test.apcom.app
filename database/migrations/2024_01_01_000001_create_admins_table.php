<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->enum('role', ['super_admin', 'admin'])->default('admin');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('notify_new_order')->default(true);
            $table->string('two_factor_secret', 255)->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
