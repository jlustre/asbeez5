<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('country_timezone')) {
            Schema::create('country_timezone', function (Blueprint $table) {
                $table->id();
                $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
                $table->foreignId('timezone_id')->constrained('timezones')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['country_id', 'timezone_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('country_timezone');
    }
};
