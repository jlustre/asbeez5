<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('addresses')) {
            Schema::create('addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('label', 64)->nullable();
                $table->string('type', 32)->nullable()->index(); // e.g., billing, shipping, other
                $table->string('address_line1', 255);
                $table->string('address_line2', 255)->nullable();
                $table->string('city', 128)->nullable();
                $table->string('state', 128)->nullable();
                $table->string('postal_code', 32)->nullable();
                $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
                $table->boolean('is_default')->default(false)->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('addresses')) {
            Schema::dropIfExists('addresses');
        }
    }
};
