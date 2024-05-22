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
        Schema::create('attendance_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
            $table->string('title');
            $table->string('session', 9);
            $table->unsignedInteger('course_id');
            $table->enum('semester', ['HARMATTAN', 'RAIN']);
            $table->enum('type', ['lab', 'lecture']);

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            // $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_lists');
    }
};
