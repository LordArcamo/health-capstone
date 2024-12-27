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
        Schema::create('risk_management', function (Blueprint $table) {
            $table->bigIncrements('riskId');
            $table->unsignedBigInteger('personalId'); // Foreign key to patients table
            $table->enum('foodIntake', ['Yes', 'No'])->nullable();
            $table->enum('physicalActivity', ['Yes', 'No'])->nullable();
            $table->enum('bloodGlucose', ['Yes', 'No'])->nullable();
            $table->float('fbsrbs', 8, 2)->nullable();
            $table->date('bloodGlucoseDate')->nullable();
            $table->enum('bloodLipids', ['Yes', 'No'])->nullable();
            $table->float('totalCholesterol', 8, 2)->nullable();
            $table->date('bloodLipidsDate')->nullable();
            $table->enum('urineKetones', ['Yes', 'No'])->nullable();
            $table->float('urineKetoneLevel', 8, 2)->nullable();
            $table->date('urineKetonesDate')->nullable();
            $table->enum('urineProtein', ['Yes', 'No'])->nullable();
            $table->float('urineProteinLevel', 8, 2)->nullable();
            $table->date('urineProteinDate')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('personalId')->references('personalId')->on('personal_information')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_management');
    }
};
