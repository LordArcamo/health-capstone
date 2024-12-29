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
        Schema::create('postpartum', function (Blueprint $table) {
            $table->bigIncrements('postpartumId');  // Custom primary key
            $table->unsignedBigInteger('prenatalId');  // Custom primary key
            $table->string('lastName', 100);
            $table->string('firstName', 100);
            $table->string('middleName', 100);
            $table->string('sex', 10);
            $table->decimal('birthLength', 5, 2);
            $table->decimal('birthWeight', 5, 2);
            $table->date('deliveryDate');
            $table->time('deliveryTime');
            $table->date('dateInitiatedBreastfeeding');
            $table->time('timeInitiatedBreastfeeding');
            $table->date('dateVitaminA');
            $table->string('dangerSignsMother', 100);
            $table->timestamps();

            $table->foreign('prenatalId')->references('prenatalId')->on('prenatal')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postpartum');
    }
};
