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
            $table->boolean('approved')->default(true);
            $table->unsignedInteger('reg_no');
            $table->date('birthdate')->nullable();
            $table->string('address')->nullable();
            $table->enum('gender', ['MALE', 'FEMALE'])->nullable();
            $table->integer('level')->nullable();
            $table->string('image')->nullable();
            $table->decimal('cgpa', 3, 2);


            $table->string('blood_group')->nullable();
            $table->string('genotype')->nullable();
            $table->string('religion')->nullable();

            $table->string('lga')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->enum('entry_mode', ['DIRECT', 'UTME'])->nullable();
            $table->timestamps();

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
