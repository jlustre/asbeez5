<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('vendors')) {
            Schema::create('vendors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('address_line1')->nullable();
                $table->string('address_line2')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('postal_code')->nullable();
                $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
                $table->foreignId('timezone_id')->nullable()->constrained('timezones')->nullOnDelete();
                $table->string('logo_url')->nullable();
                $table->string('banner_url')->nullable();
                $table->decimal('commission_rate', 5, 2)->default(10.00);
                $table->boolean('is_active')->default(true);
                $table->string('verification_status')->default('pending');
                $table->decimal('rating_avg', 3, 2)->default(0.00);
                $table->unsignedInteger('rating_count')->default(0);
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
