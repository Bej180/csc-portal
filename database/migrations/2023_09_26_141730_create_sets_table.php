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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('name', 45);
            $table->string('token')->nullable();
            $table->string('description')->nullable();
            $table->year('start_year', 4);
            $table->year('end_year', 4);
            $table->timestamps();

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
