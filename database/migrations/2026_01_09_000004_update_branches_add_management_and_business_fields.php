<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->unsignedBigInteger('manager_employee_id')->nullable()->after('longitude');
            $table->unsignedBigInteger('assistant_manager_employee_id')->nullable()->after('manager_employee_id');
            $table->string('pricing_type')->nullable()->after('assistant_manager_employee_id');
            $table->json('opening_hours')->nullable()->after('pricing_type');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['manager_employee_id', 'assistant_manager_employee_id', 'pricing_type', 'opening_hours']);
        });
    }
};
