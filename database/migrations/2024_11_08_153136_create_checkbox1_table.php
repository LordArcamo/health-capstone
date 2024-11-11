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
        Schema::create('checkbox1', function (Blueprint $table) {
            // Change primary key to 'checkbox1ID'
            $table->bigIncrements('checkbox1ID');  // Primary Key: 'checkbox1ID'
            
            // Foreign Key to 'general_trimester' table
            $table->unsignedBigInteger('generalTrimesterID');  // Foreign Key Column
            
            // Boolean fields
            $table->boolean('prenatal_checkup')->default(false);  
            $table->boolean('pe_done')->default(false);  
            $table->boolean('prenatal_record')->default(false);  
            $table->boolean('birth_plan_done')->default(false);  
            $table->boolean('nkfda')->default(false);  
            $table->boolean('health_teachings')->default(false);  
            $table->boolean('referred_for')->default(false);  
            $table->boolean('healthy_diet')->default(false);  
            $table->boolean('fes04_folic')->default(false);  
            $table->unsignedSmallInteger('folic_acid')->nullable();  
            $table->string('fhb', 50)->nullable();  
            $table->string('position', 50)->nullable();  
            $table->string('presentation', 50)->nullable();  
            $table->string('fundal_height', 50)->nullable();  
            
            $table->timestamps();  

            $table->foreign('generalTrimesterID')
                  ->references('generalTrimesterID')  
                  ->on('general_trimester')    
                  ->onDelete('cascade');      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkbox1');
    }
};
