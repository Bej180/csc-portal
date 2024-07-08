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
            $table->enum('gender', ['MALE', 'FEMALE']);
            
            
            $table->string('name');
            $table->tinyInteger('log_attempts', false, true)->default(0);
            $table->timestamp('unlock_duration')->nullable();
            $table->string('activation_token')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->enum('role', ['admin', 'student', 'staff'])->default('student');
            $table->string('rank')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('last_seen_announcement')->default(0);
            

            $table->enum('two_factor_status', ['enabled', 'disabled'])->default('disabled');
            $table->boolean('two_factor_locked')->default(false);
            $table->date('two_factor_confirmed_at')->nullable();
            $table->enum('two_factor_method', ['sms', 'email'])->default('email');
            $table->enum('two_factor_frequency', ['always', 'new_device'])->nullable();
            $table->longText('two_factor_recovery_codes')->nullable();
            $table->string('two_factor_secret', 64)->nullable();
            
            $table->string('password');
            $table->string('pin', 64);
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
