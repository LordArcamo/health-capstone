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
            $table->id();
            $table->string('firstName', 100);
            $table->string('lastName', 100);
            $table->string('middleName', 100);
            $table->string('suffix', 10);
            $table->string('address', 255);
            $table->integer('age');
            $table->date('birthdate');
            $table->string('contact', 15); 
            $table->string('sex', 10); 
            $table->string('birthplace', 100);
            $table->string('bloodtype', 5); 
            $table->string('mothername', 100);
            $table->string('dswdNhts', 100);
            $table->string('facilityHouseholdno', 100);
            $table->string('houseHoldno', 100);
            $table->string('fourpsmember', 100);
            $table->string('PCBMember', 100);
            $table->string('philhealthMember', 100);
            $table->string('statusType', 50); 
            $table->string('philhealthNo', 15); 
            $table->string('ifMember', 10); 
            $table->string('familyMember', 100);
            $table->string('ttStatus', 10); 
            $table->date('dateAssesed');
            $table->date('date');
            $table->string('place', 100);
            $table->string('guardian', 100);
            $table->timestamps();
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
