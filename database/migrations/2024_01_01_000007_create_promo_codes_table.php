<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('code', 100)->unique();
            $table->decimal('discount_pct', 5, 2);
            $table->dateTime('valid_from');
            $table->dateTime('valid_until')->nullable();
            $table->integer('usage_limit_per_user')->default(1);
            $table->enum('scope', ['all', 'specific_user', 'group'])->default('all');
            $table->string('bat_id_specific', 100)->nullable();
            $table->foreignId('user_group_id')->nullable()->constrained('user_groups')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
