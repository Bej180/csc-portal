<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 11);
            $table->enum('status', ['PRESENT', 'ABSENT']);
            
            $table->unsignedBigInteger('attendance_id');
            
            $table->timestamps();

            $table->foreign('attendance_id')->references('id')->on('attendance_lists')->onDelete('cascade');
            // $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students_attendances');
    }
};
