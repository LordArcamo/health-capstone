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
        if (!Schema::hasTable('postpartum')) {
            Schema::create('postpartum', function (Blueprint $table) {
                $table->bigIncrements('postpartumId');  // Custom primary key
                $table->unsignedBigInteger('prenatalConsultationDetailsID');
                $table->unsignedBigInteger('id');
                $table->string('lastName', 100);
                $table->string('firstName', 100);
                $table->string('middleName', 100);
                $table->string('sex', 10);
                $table->string('prenatalDelivered', 100);
                $table->string('placeDelivered', 100);
                $table->string('modeOfDelivery', 100);
                $table->decimal('birthLength', 5, 2);
                $table->decimal('birthWeight', 5, 2);
                $table->date('deliveryDate');
                $table->time('deliveryTime');
                $table->string('attendantBirth', 100);
                $table->date('dateInitiatedBreastfeeding');
                $table->time('timeInitiatedBreastfeeding');
                $table->date('dateOfPostpartumVisitTwentyFourHoursDelivery');
                $table->date('dateOfPostpartumVisitOneWeekDelivery');
                $table->date('dateVitaminA');
                $table->date('dateIronGiven');
                $table->decimal('noIronGiven', 5, 2);
                $table->string('dangerSignsMother', 100);
                $table->string('dangerSignsBaby', 100);
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postpartum');
    }
};
