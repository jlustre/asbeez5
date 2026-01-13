<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                if (! Schema::hasColumn('employees', 'permission_level')) {
                    $table->unsignedTinyInteger('permission_level')->default(1)->after('role');
                    $table->index('permission_level');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                if (Schema::hasColumn('employees', 'permission_level')) {
                    $table->dropIndex(['permission_level']);
                    $table->dropColumn('permission_level');
                }
            });
        }
    }
};
