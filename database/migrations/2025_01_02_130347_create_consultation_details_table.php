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
        Schema::create('consultation_details', function (Blueprint $table) {
            $table->bigIncrements('consultationDetailsID'); // Primary key for ITR table
            $table->unsignedBigInteger('personalId'); // Foreign key referencing personal_information
            $table->unsignedBigInteger('id');
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
            $table->string('natureOfVisit', 100);
            $table->string('visitType', 50);
            

            $table->timestamps();

            // Add foreign key constraint for personalId
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
        Schema::dropIfExists('consultation_details');
    }
};
