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
        Schema::create('itr', function (Blueprint $table) {
            $table->bigIncrements('itrId'); // Primary key for ITR table
            $table->unsignedBigInteger('personalId'); // Foreign key referencing personal_information
            $table->date('consultationDate');
            $table->time('consultationTime');
            $table->string('modeOfTransaction', 50);
            $table->string('bloodPressure', 20); 
            $table->decimal('temperature', 5, 2); 
            $table->decimal('height', 5, 2); 
            $table->decimal('weight', 5, 2); 
            $table->string('referredFrom', 255);
            $table->string('referredTo', 255);
            $table->string('reasonsForReferral', 255);
            $table->string('referredBy', 255);
            $table->string('providerName', 100);
            $table->string('natureOfVisit', 100);
            $table->string('visitType', 50); 
            $table->string('chiefComplaints', 255);
            $table->string('diagnosis', 255);
            $table->string('medication', 255);
            

            $table->timestamps();

            // Add foreign key constraint for personalId
            $table->foreign('personalId')
                ->references('personalId')
                ->on('personal_information')
                ->onDelete('cascade'); // Optional: delete ITR record if related personal information is deleted
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itr');
    }
};
