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
        Schema::create('general_trimester', function (Blueprint $table) {
            $table->bigIncrements('generalTrimesterID');
            $table->unsignedBigInteger('prenatalConsultationDetailsID');
            $table->unsignedBigInteger('id');
            $table->date('date_of_visit');
            $table->decimal('weight', 5, 2);
            $table->string('bp', 20);
            $table->unsignedSmallInteger('heart_rate');
            $table->unsignedTinyInteger('aog_months');
            $table->unsignedTinyInteger('aog_days');
            $table->string('trimester', 10);
            $table->timestamps();

            $table->foreign('prenatalConsultationDetailsID')
                  ->references('prenatalConsultationDetailsID')
                  ->on('prenatal_consultation_details')
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
        Schema::dropIfExists('general_trimester');
    }
};
