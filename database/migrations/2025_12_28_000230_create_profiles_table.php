<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('profiles')) {
            Schema::create('profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('phone')->nullable();
                $table->string('address_line1')->nullable();
                $table->string('address_line2')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('postal_code')->nullable();
                $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
                $table->foreignId('timezone_id')->nullable()->constrained('timezones')->nullOnDelete();
                $table->string('background_url')->nullable();
                $table->date('birthdate')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
