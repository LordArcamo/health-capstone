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
            $table->date('consultationDate');
            $table->time('consultationTime');
            $table->string('modeOfTransaction', 50);
            $table->string('bloodPressure', 20); 
            $table->decimal('temperature', 5, 2); 
            $table->decimal('height', 5, 2); 
            $table->decimal('weight', 5, 2); 
            $table->string('providerName', 100);
            $table->string('natureOfVisit', 100);
            $table->string('visitType', 50); 
            $table->string('chiefComplaints', 255);
            $table->string('diagnosis', 255);
            $table->string('medication', 255);
            
            $table->timestamps();
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
