<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->string('suite', 50);
            $table->string('group', 100);
            $table->string('test_name', 255);
            $table->string('status', 20)->default('pending');
            $table->text('comment')->nullable();
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->unique(['suite', 'group', 'test_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
