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
        if (!Schema::hasTable('vendor_commission_rates')) {
            Schema::create('vendor_commission_rates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
                $table->foreignId('commission_rate_id')->constrained('commission_rates')->cascadeOnDelete();
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('priority')->default(0);
                $table->string('notes')->nullable();
                $table->timestamps();

                $table->index(['vendor_id', 'is_active']);
                $table->index(['commission_rate_id']);
                $table->unique(['vendor_id', 'commission_rate_id', 'starts_at'], 'uniq_vendor_rate_start');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_commission_rates');
    }
};
