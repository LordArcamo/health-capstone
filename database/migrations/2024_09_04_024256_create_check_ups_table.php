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
            $table->string('fname');
            $table->string('lname');
            $table->string('mname');
            $table->string('suffix');
            $table->string('address');
            $table->integer('age');
            $table->date('dob');
            $table->integer('contact_num');
            $table->string('sex');
            $table->date('date_of_consult');
            $table->time('time_of_consult');
            $table->integer('bp');
            $table->integer('height');
            $table->float('weight');
            $table->integer('temperature');
            $table->string('attendname');
            $table->string('nature_visit');
            $table->string('type_consult');
            $table->string('diagnosis');
            $table->string('treatment');
            $table->string('nurse');
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
