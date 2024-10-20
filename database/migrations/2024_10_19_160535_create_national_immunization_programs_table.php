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
            $table->string('firstName');
            $table->string('lastName');
            $table->string('middleName');
            $table->string('suffix');
            $table->string('address');
            $table->integer('age');
            $table->date('birthdate');
            $table->string('contact');
            $table->string('sex');
            $table->string('birthplace');
            $table->string('bloodtype');
            $table->string('mothername');
            $table->string('dswdNhts');
            $table->string('facilityHouseholdno');
            $table->string('houseHoldno');
            $table->string('fourpsmember');
            $table->string('PCBMember');
            $table->string('philhealthMember');
            $table->string('statusType');
            $table->string('philhealthNo');
            $table->string('ifMember');
            $table->string('familyMember');
            $table->string('ttstatus');
            $table->date('dateAssesed');
            $table->date('date');
            $table->string('place');
            $table->string('guardian');
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
