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
        Schema::create('national_immunization_programs', function (Blueprint $table) {
            $table->bigIncrements('immunizationId'); 
            $table->unsignedBigInteger('personalId'); // Foreign key column
            $table->unsignedBigInteger('id');
            $table->string('birthplace', 100);
            $table->string('bloodtype', 5);
            $table->string('mothername', 100);
            $table->string('dswdNhts', 100);
            $table->string('facilityHouseholdno', 100);
            $table->string('houseHoldno', 100);
            $table->string('fourpsmember', 100);
            $table->string('PCBMember', 100);
            $table->string('philhealthStatus', 50);
            $table->string('philhealthNo', 15);
            $table->string('ifMember', 100);
            $table->string('familyMember', 100);
            $table->string('ttStatus', 10);
            $table->date('dateAssesed');
            $table->date('date');
            $table->string('place', 100);
            $table->string('guardian', 100);
            $table->timestamps();

            // Setting up the foreign key constraint
            $table->foreign('personalId')->references('personalId')->on('personal_information')->onDelete('cascade');
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('national_immunization_programs');
    }
};
