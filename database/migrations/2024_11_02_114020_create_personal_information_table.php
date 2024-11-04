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
        Schema::create('personal_information', function (Blueprint $table) {
            $table->bigIncrements('personalId');  // Custom primary key
            $table->string('firstName', 100);      // Limit to 100 characters
            $table->string('lastName', 100);       // Limit to 100 characters
            $table->string('middleName', 100);     // Limit to 100 characters
            $table->string('suffix', 10);          // Limit to 10 characters
            $table->string('purok', 100);           // Limit to 50 characters
            $table->string('barangay', 100);       // Limit to 100 characters
            $table->integer('age');
            $table->date('birthdate');
            $table->string('contact', 15);         // Limit to 15 characters
            $table->string('sex', 10);             // Limit to 10 characters
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_information');
    }
};
