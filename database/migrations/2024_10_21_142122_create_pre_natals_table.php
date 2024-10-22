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
        Schema::create('pre_natals', function (Blueprint $table) {
            $table->id();
            $table->string('firstName', 100);        
            $table->string('lastName', 100);
            $table->string('middleName', 100);
            $table->string('address');               
            $table->integer('age');                 
            $table->date('birthdate');              
            $table->string('modeOfTransaction', 50); 
            $table->date('consultationDate');        
            $table->time('consultationTime');        
            $table->string('bloodPressure', 10);     
            $table->decimal('temperature', 4, 1);        
            $table->decimal('height', 5, 2);
            $table->decimal('weight', 5, 2);             
            $table->string('providerName', 100);     
            $table->string('nameOfSpouse', 100);     
            $table->string('emergencyContact', 15); 
            $table->string('fourMember', 50);        
            $table->string('philhealthStatus', 20); 
            $table->string('philhealthId', 20)->nullable();      
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_natals');
    }
};
