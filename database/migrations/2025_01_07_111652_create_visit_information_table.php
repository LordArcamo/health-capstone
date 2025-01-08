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
        if (!Schema::hasTable('visit_information')) {
            Schema::create('visit_information', function (Blueprint $table) {
                $table->bigIncrements('visitInformationID'); // Primary key
                $table->unsignedBigInteger('consultationDetailsID'); // Foreign key
                $table->unsignedBigInteger('id');
                $table->string('chiefComplaints', 255);
                $table->string('diagnosis', 255);
                $table->string('medication', 255);
                $table->timestamps();

                $table->foreign('consultationDetailsID')
                    ->references('consultationDetailsID')
                    ->on('consultation_details')
                    ->onDelete('cascade');

                $table->foreign('id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            });
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_information');
    }
};
