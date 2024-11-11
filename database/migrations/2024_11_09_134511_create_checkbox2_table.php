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
        Schema::create('checkbox2', function (Blueprint $table) {
            $table->id('checkbox2ID');
            $table->unsignedBigInteger('generalTrimesterID');
            // Checkbox items
            $table->boolean('prenatal_record')->default(false);
            $table->boolean('reminded_importance')->default(false);
            $table->boolean('health_teachings')->default(false);
            $table->boolean('reminded_dangers')->default(false);
            $table->boolean('healthy_diet')->default(false);
            $table->boolean('breast_feeding')->default(false);
            $table->boolean('compliane_routine')->default(false);
            $table->boolean('referred_utz')->default(false);
            $table->boolean('fes04_folic')->default(false);
            
            // Folic acid tabs (if specific tab count is needed)
            $table->integer('folic_acid_tabs')->nullable();
            
            $table->timestamps();

            $table->foreign('generalTrimesterID')->references('generalTrimesterID')->on('general_trimester')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkbox2');
    }
};
