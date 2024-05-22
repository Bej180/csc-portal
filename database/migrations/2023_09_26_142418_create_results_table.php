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

        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('score');
            $table->unsignedBigInteger('uploaded_by');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('course_id');
            $table->string('reg_no', 11);
            $table->unsignedTinyInteger('grading_id')->default(1);
            $table->integer('level');
            $table->smallInteger('lab')->nullable();
            $table->smallInteger('exam')->nullable();
            $table->smallInteger('test')->nullable();
            $table->enum('status', ['INCOMPLETE','READY','DRAFT','PENDING','APPROVED'])->default('PENDING');
            // $table->enum('status', ['incomplete','ready','draft','completed','approved'])->default('incomplete');
            $table->enum('remark', ['FAILED', 'PASSED'])->nullable();
            $table->unsignedInteger('grade_points')->default(0);
            $table->char('grade', 1);
            $table->unsignedTinyInteger('units');
            $table->string('session', 9);
            $table->string('reference_id', 64);
            $table->enum('semester', ['RAIN', 'HARMATTAN']);
            $table->boolean('approved')->default(false);
            $table->foreign('course_id')->references('id')->on('courses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
