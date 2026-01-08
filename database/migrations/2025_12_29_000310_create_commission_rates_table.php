<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('commission_rates')) {
            Schema::create('commission_rates', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['fixed', 'percent']);
                $table->string('tier');
                $table->string('description')->nullable();
                // For percent, store e.g. 10.00 meaning 10%; for fixed, store currency amount
                $table->decimal('rate', 10, 4);
                // Simple built-in qualifications; additional rules can be stored in JSON below
                $table->integer('min_orders')->nullable();
                $table->integer('max_orders')->nullable();
                $table->decimal('min_order_value', 12, 2)->nullable();
                $table->decimal('max_order_value', 12, 2)->nullable();
                $table->decimal('min_revenue', 14, 2)->nullable();
                $table->json('qualifications')->nullable();
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['is_active']);
                $table->unique(['tier', 'type', 'starts_at'], 'uniq_vendor_tier_type_start');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_rates');
    }
};
