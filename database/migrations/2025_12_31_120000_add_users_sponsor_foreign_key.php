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
        Schema::table('users', function (Blueprint $table) {
            // Ensure column exists and add foreign key
            if (Schema::hasColumn('users', 'sponsor_id')) {
                $table->foreign('sponsor_id')
                    ->references('id')
                    ->on('users');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'sponsor_id')) {
                $table->dropForeign(['sponsor_id']);
            }
        });
    }
};
