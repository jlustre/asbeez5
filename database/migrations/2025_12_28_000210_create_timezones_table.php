<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('timezones')) {
            Schema::create('timezones', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique(); // e.g., "America/New_York"
                $table->string('abbreviation', 10)->nullable(); // e.g., "EST"
                $table->string('utc_offset', 6)->nullable(); // e.g., "+05:30"
                $table->integer('offset_minutes')->nullable(); // integer minutes from UTC
                $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('timezones');
    }
};
