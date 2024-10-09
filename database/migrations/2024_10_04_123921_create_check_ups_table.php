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
        Schema::create('check_ups', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('middleName');
            $table->string('suffix');
            $table->string('address');
            $table->integer('age');
            $table->date('birthdate');
            $table->string('contact');
            $table->string('sex');
            $table->date('consultationDate');
            $table->time('consultationTime');
            $table->string('modeOfTransaction');
            $table->string('bloodPressure');
            $table->double('temperature');
            $table->integer('height');
            $table->double('weight');
            $table->string('providerName');
            $table->string('natureOfVisit');
            $table->string('visitType');
            $table->string('chiefComplaints');
            $table->string('diagnosis');
            $table->string('medication');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_ups');
    }
};
