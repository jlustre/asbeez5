<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Drop FK first, then columns
            if (Schema::hasColumn('profiles', 'country_id')) {
                $table->dropConstrainedForeignId('country_id');
            }
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'address_line1',
                'address_line2',
                'city',
                'state',
                'postal_code',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
        });
    }
};
