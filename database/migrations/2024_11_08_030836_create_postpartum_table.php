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
            $table->string('lastName', 100);
            $table->string('firstName', 100);
            $table->string('middleName', 100);
            $table->string('sex', 10);
            $table->decimal('birthLength', 5, 2);
            $table->decimal('birthWeight', 5, 2);
            $table->string('prenatalDelivered', 100);
            $table->string('placeDelivered', 100);
            $table->string('modeOfDelivery', 100);
            $table->string('attendantAtBirth', 100);
            $table->date('deliveryDate');
            $table->time('deliveryTime');
            $table->string('dangerSignsMother', 100);
            $table->string('dangerSignsBaby', 100);
            $table->timestamps();
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