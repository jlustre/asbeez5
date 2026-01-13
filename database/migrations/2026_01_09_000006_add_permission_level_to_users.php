<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'permission_level')) {
                $table->unsignedTinyInteger('permission_level')->default(1)->after('remember_token');
                $table->index('permission_level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'permission_level')) {
                $table->dropIndex(['permission_level']);
                $table->dropColumn('permission_level');
            }
        });
    }
};
