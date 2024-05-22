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
        Schema::create('deans', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); 
            $table->timestamps();
            $table->string('title')->nullable();
            $table->string('staff_id');
            $table->string('image')->nullable();
            $table->enum('gender', ['MALE', 'FEMALE']);
            $table->string('address');
            $table->string('email');
            $table->string('phone')->nullale();
            $table->string('password');

            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deans');
    }
};
