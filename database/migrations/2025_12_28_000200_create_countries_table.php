<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('countries')) {
            Schema::create('countries', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->char('iso2', 2)->unique();
                $table->char('iso3', 3)->unique()->nullable();
                $table->string('phone_code', 10)->nullable();
                $table->string('currency_code', 3)->nullable();
                $table->string('currency_name')->nullable();
                $table->string('region')->nullable();
                $table->string('subregion')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
