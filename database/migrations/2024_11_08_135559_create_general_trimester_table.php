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
            $table->unsignedBigInteger('prenatalId');
            $table->date('date_of_visit');
            $table->decimal('weight', 5, 2);
            $table->string('bp', 20);
            $table->unsignedSmallInteger('heart_rate');
            $table->unsignedTinyInteger('aog_months');
            $table->unsignedTinyInteger('aog_days'); 
            $table->string('trimester', 10);
            $table->timestamps();

            $table->foreign('prenatalId')
                  ->references('prenatalId')
                  ->on('prenatal')
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
