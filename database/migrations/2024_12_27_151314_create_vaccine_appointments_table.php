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
        Schema::create('vaccine_appointments', function (Blueprint $table) {
            $table->bigIncrements('vacAppointmentId');
            $table->unsignedBigInteger('vaccinationId');
            $table->date('dateOfVisit');
            $table->decimal('weight', 5, 2);
            $table->decimal('height', 5, 2);
            $table->decimal('temperature', 4, 2);
            $table->string('antigenGiven');
            $table->enum('exclusivelyBreastfed', ['Yes', 'No', 'None'])->default('None');
            $table->string('injectedBy');
            $table->date('nextAppointment');
            $table->timestamps();

            $table->foreign('vaccinationId')->references('vaccinationId')->on('vaccination_records')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccine_appointments');
    }
};
