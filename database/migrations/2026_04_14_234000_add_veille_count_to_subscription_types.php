<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_types', function (Blueprint $table) {
            $table->integer('veille_count')->nullable()->after('veille_robotisee');
            $table->boolean('veille_unlimited')->default(false)->after('veille_count');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_types', function (Blueprint $table) {
            $table->dropColumn(['veille_count', 'veille_unlimited']);
        });
    }
};
