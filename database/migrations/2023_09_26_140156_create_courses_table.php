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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('reference_id');
            
            $table->string('name');
            $table->string('code', 25);
            $table->longText('outline')->nullable();
            $table->enum('semester', ['RAIN', 'HARMATTAN']);
            $table->unsignedBigInteger('cordinator')->nullable();

            $table->boolean('cordinator_input_lab_score')->default(false);

            $table->enum('option', ['COMPULSARY', 'ELECTIVE'])->default('COMPULSARY');

            $table->string('level', 3);
            $table->boolean('has_practical')->default(false);
            $table->unsignedTinyInteger('units');
            $table->integer('prerequisite')->default(0);
            $table->string('image')->nullable();

            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
