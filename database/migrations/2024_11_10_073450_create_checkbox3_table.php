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
        Schema::create('checkbox3', function (Blueprint $table) {
            $table->bigIncrements('checkbox3ID');
            $table->unsignedBigInteger('generalTrimesterID');
            
            // Add a column to identify trimester type
            $table->enum('trimester_type', [3, 4, 5]); // 3 for trimester 3, 4 for trimester 4, and 5 for trimester 5

            // Checkbox fields as booleans
            $table->boolean('prenatal_checkup')->default(false);
            $table->boolean('pe_done')->default(false);
            $table->boolean('prenatal_record')->default(false);
            $table->boolean('birth_plan_done')->default(false);
            $table->boolean('health_teachings')->default(false);
            $table->boolean('reminded_dangers')->default(false);
            $table->boolean('healthy_diet')->default(false);
            $table->boolean('breast_feeding')->default(false);
            $table->boolean('compliance_routine')->default(false);
            $table->boolean('referred_utz')->default(false);
            $table->boolean('information_newborn')->default(false);
            $table->boolean('fes04_folic')->default(false);

            // Trimester 4 and 5-specific field
            $table->boolean('information_family')->nullable(); // For trimester 4 and 5

            $table->integer('folic_acid')->nullable();
            $table->string('fhb')->nullable();
            $table->string('position')->nullable();
            $table->string('presentation')->nullable();
            $table->string('fundal_height')->nullable();

            $table->timestamps();

            // Foreign key for generalTrimesterID
            $table->foreign('generalTrimesterID')->references('generalTrimesterID')->on('general_trimester')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkboxes');
    }
};
