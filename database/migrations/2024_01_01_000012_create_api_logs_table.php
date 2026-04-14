<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('direction', ['outgoing', 'incoming']);
            $table->string('event', 100);
            $table->string('bat_id', 100)->nullable();
            $table->string('status', 50);
            $table->integer('attempts')->default(0);
            $table->json('payload')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
