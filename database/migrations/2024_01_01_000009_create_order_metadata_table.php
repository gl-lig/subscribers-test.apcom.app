<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('os', 100)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('language', 20)->nullable();
            $table->string('screen_resolution', 20)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->text('referer')->nullable();
            $table->string('session_id', 255)->nullable();
            $table->timestamp('server_timestamp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_metadata');
    }
};
