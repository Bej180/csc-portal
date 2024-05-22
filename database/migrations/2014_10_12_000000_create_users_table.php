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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('username', 50)->unique()->nullable();
            $table->string('phone', 12)->nullable();
            $table->string('name');
            $table->string('unique_id', 10)->unique()->nullable();
            $table->tinyInteger('logAttempts', false, true)->default(0);
            $table->timestamp('unlockDuration', false, true)->nullable();
            $table->string('activation_token')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->enum('role', ['admin', 'student', 'staff'])->default('student');
            $table->string('rank')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('token')->nullable();
            $table->rememberToken();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
