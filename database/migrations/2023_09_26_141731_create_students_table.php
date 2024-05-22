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
        Schema::create('students', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('set_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('reg_no')->nullable();
            $table->string('phone', 12)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('address')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->integer('level')->nullable();
            $table->string('image')->nullable();
            $table->decimal('cgpa', 3, 2);


            $table->string('blood_group')->nullable();
            $table->string('genotype')->nullable();
            $table->string('religion')->nullable();

            $table->string('lga')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();

            // Establish relationships
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
