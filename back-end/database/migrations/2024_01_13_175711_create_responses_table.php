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
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diagnosisID');
            $table->foreign('diagnosisID')->references('id')->on('diagnoses')->onDelete('cascade');
            $table->unsignedBigInteger('questionID');
            $table->foreign('questionID')->references('id')->on('questions')->onDelete('cascade');
            $table->unsignedBigInteger('optionID');
            $table->foreign('optionID')->references('id')->on('options')->onDelete('cascade');
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
        Schema::dropIfExists('responses');
    }
};
