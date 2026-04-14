<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();
            $table->foreignId('subscriber_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_type_id')->constrained()->restrictOnDelete();
            $table->integer('duration_months');
            $table->decimal('price_catalogue', 10, 2);
            $table->decimal('discount_duration_pct', 5, 2)->default(0);
            $table->string('promo_code', 100)->nullable();
            $table->decimal('discount_promo_pct', 5, 2)->default(0);
            $table->decimal('price_paid', 10, 2);
            $table->decimal('prorata_deducted', 10, 2)->default(0);
            $table->enum('status', ['active', 'expired', 'replaced'])->default('active');
            $table->timestamp('concluded_at')->nullable();
            $table->date('starts_at');
            $table->date('expires_at');
            $table->string('datatrans_transaction_id', 100)->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->uuid('invoice_token')->unique()->nullable();
            $table->string('invoice_url', 500)->nullable();
            $table->timestamp('cgv_accepted_at')->nullable();
            $table->string('cgv_version', 50)->nullable();
            $table->timestamp('expiry_notified_at')->nullable();
            $table->foreignId('replaced_by_order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->text('replacement_note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('expires_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
