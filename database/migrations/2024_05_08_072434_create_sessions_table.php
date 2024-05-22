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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->uniqid();
            $table->enum('active_semester', ['HARMATTAN', 'RAIN'])->default('HARMATTAN');
            $table->enum('harmattan_course_registration_status', ['OPEN', 'CLOSED'])->default('OPEN');
            $table->enum('rain_course_registration_status', ['OPEN', 'CLOSED'])->default('CLOSED');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
};
