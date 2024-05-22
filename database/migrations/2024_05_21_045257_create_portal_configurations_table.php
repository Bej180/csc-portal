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
        Schema::create('portal_configurations', function (Blueprint $table) {
            $table->id();
            $table->boolean('two_factor_enabled')->default(true);
            $table->enum('current_semester', ['HARMATTAN', 'RAIN']);
            $table->string('current_session', 9);
            $table->boolean('use_pin_for_course_registration')->default(false);
            $table->boolean('allow_class_advisors_to_add_students')->default(false);
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
        Schema::dropIfExists('portal_configurations');
    }
};
