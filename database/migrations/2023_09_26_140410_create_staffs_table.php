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
        Schema::create('staffs', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); 
            $table->unsignedBigInteger('created_by')->nullable();

            $table->date('birthdate')->nullable();
            $table->string('address')->nullable();
            $table->string('staff_id')->nullable();
            $table->boolean('is_class_advisor')->default(false);
            $table->boolean('is_hod')->default(false);
            
            $table->enum('designation', ['lecturer', 'technologist']);
            
            

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
        Schema::dropIfExists('staffs');
    }
};
