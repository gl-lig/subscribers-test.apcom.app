<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('user_groups')->cascadeOnDelete();
            $table->string('bat_id', 100);
            $table->timestamps();

            $table->unique(['group_id', 'bat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_group_members');
    }
};
