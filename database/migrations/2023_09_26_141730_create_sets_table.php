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
        Schema::create('sets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisor_id')->nullable();
            $table->unsignedBigInteger('course_rep')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('name', 45);
            $table->string('token')->nullable();
            $table->string('description')->nullable();
            $table->string('department')->nullable();
            $table->year('start_year', 4);
            $table->year('end_year', 4);
            $table->timestamps();

            $table->foreign('advisor_id')->references('id')->on('staffs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sets');
    }
};
