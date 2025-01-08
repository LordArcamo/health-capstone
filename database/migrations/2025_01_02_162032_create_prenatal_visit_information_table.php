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
        Schema::create('prenatal_visit_information', function (Blueprint $table) {
            $table->bigIncrements('prenatalVisitInformationID');
            $table->unsignedBigInteger('prenatalConsultationDetailsID');
            $table->unsignedBigInteger('id');
            $table->string('menarche', 10);
            $table->string('sexualOnset', 10);
            $table->string('periodDuration', 10);
            $table->string('birthControl', 50);
            $table->string('intervalCycle', 10);
            $table->string('menopause', 10);
            $table->date('lmp');
            $table->date('edc');
            $table->string('gravidity', 5);
            $table->string('parity', 5);
            $table->string('term', 5);
            $table->string('preterm', 5);
            $table->string('abortion', 5);
            $table->string('living', 5);
            $table->string('syphilisResult', 10);
            $table->string('penicillin', 10);
            $table->decimal('hemoglobin', 5, 2);
            $table->decimal('hematocrit', 5, 2);
            $table->string('urinalysis', 255);
            $table->string('ttStatus', 10);
            $table->date('tdDate');
            $table->timestamps();

            // Set foreign key constraint
            $table->foreign('prenatalConsultationDetailsID')->references('prenatalConsultationDetailsID')->on('prenatal_consultation_details')->onDelete('cascade');
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
        Schema::dropIfExists('prenatal_visit_information');
    }
};
