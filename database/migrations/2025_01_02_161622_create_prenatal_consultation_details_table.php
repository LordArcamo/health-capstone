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
        Schema::create('prenatal_consultation_details', function (Blueprint $table) {
            $table->bigIncrements('prenatalConsultationDetailsID'); // Renamed the ID to prenatalId
            $table->unsignedBigInteger('personalId'); // Change to unsignedBigInteger for foreign key
            $table->unsignedBigInteger('id');
            $table->string('modeOfTransaction', 50); 
            $table->date('consultationDate');        
            $table->time('consultationTime');        
            $table->string('bloodPressure', 10);     
            $table->decimal('temperature', 4, 1);        
            $table->decimal('height', 5, 2);
            $table->decimal('weight', 5, 2);             
            $table->string('providerName', 100);     
            $table->string('nameOfSpouse', 100);     
            $table->string('emergencyContact', 15); 
            $table->string('fourMember', 50);        
            $table->string('philhealthStatus', 20); 
            $table->string('philhealthNo', 20)->nullable();      
            $table->timestamps();

            // Set foreign key constraint
            $table->foreign('personalId')
                ->references('personalId')
                ->on('personal_information')
                ->onDelete('cascade');

            $table->foreign('id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prenatal_consultation_details');
    }
};
