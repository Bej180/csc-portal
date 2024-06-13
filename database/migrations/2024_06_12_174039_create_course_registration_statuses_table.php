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
        Schema::create('course_registration_statuses', function (Blueprint $table) {
            $table->id();
            $table->enum('semester', ['HARMATTAN', 'RAIN']);
            $table->string('session', 9);
            $table->timestamp('close_on')->nullable();

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
        Schema::dropIfExists('course_registration_statuses');
    }
};
