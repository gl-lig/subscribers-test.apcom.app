<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_types', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['online', 'admin_only', 'inactive'])->default('inactive');
            $table->integer('sort_order')->default(0);
            $table->integer('parcelles_count')->nullable();
            $table->boolean('parcelles_unlimited')->default(false);
            $table->integer('alertes_count')->default(0);
            $table->integer('stockage_go')->nullable();
            $table->boolean('stockage_unlimited')->default(false);
            $table->boolean('cloud_externe')->default(false);
            $table->boolean('lot_sauvegarde')->default(false);
            $table->boolean('workspace_enabled')->default(false);
            $table->integer('workspace_count')->nullable();
            $table->boolean('workspace_unlimited')->default(false);
            $table->decimal('price_chf', 10, 2);
            $table->decimal('discount_36_months', 5, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_types');
    }
};
