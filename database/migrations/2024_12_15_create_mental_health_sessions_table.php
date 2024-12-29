<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mental_health_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->enum('status', ['active', 'finished'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mental_health_sessions');
    }
};
