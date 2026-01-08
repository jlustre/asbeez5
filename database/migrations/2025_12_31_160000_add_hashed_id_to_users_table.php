<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'hashed_id')) {
                $table->string('hashed_id', 10)->nullable()->unique()->after('id');
            }
        });

        // Backfill existing users
        if (function_exists('hash_id')) {
            DB::table('users')->select(['id'])->orderBy('id')->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    $code = hash_id((int) $row->id);
                    DB::table('users')->where('id', $row->id)->update(['hashed_id' => $code]);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'hashed_id')) {
                $table->dropUnique('users_hashed_id_unique');
                $table->dropColumn('hashed_id');
            }
        });
    }
};
