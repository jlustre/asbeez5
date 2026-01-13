<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('employee_code')->nullable()->unique();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('role')->default('cashier');
            $table->unsignedTinyInteger('permission_level')->default(1);
            // Store hashed PINs; bcrypt is ~60 chars, allow up to 255
            $table->string('pos_pin', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('hired_at')->nullable();
            $table->timestamp('terminated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
