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
        Schema::create('vaccination_records', function (Blueprint $table) {
            $table->bigIncrements('vaccinationId');
            $table->unsignedBigInteger('personalId');
            $table->unsignedBigInteger('id');
            $table->string('vaccineCategory', 50);  // Limited to 50 chars
            $table->string('vaccineType', 100);     // Limited to 100 chars
            $table->date('dateOfVisit');
            $table->integer('ageInMonths')->nullable();
            $table->integer('ageInYears')->nullable();
            $table->decimal('weight', 5, 2);        // Weight in kg with 2 decimal places
            $table->decimal('height', 5, 2);        // Height in cm with 2 decimal places
            $table->decimal('temperature', 4, 1);   // Temperature with 1 decimal place
            $table->string('antigenGiven', 100);    // Limited to 100 chars
            $table->string('injectedBy', 100);      // Limited to 100 chars
            $table->string('exclusivelyBreastfed', 20)->nullable(); // Limited to 20 chars
            $table->date('nextAppointment')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('personalId')
                  ->references('personalId')
                  ->on('personal_information')
                  ->onDelete('cascade');
            
            $table->foreign('id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Indexes for better query performance
            $table->index('vaccineCategory');
            $table->index('vaccineType');
            $table->index('dateOfVisit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccination_records');
    }
};