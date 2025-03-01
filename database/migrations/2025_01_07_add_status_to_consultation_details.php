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
        Schema::table('consultation_details', function (Blueprint $table) {
            $table->string('status')->default('in queue');
            $table->timestamp('completed_at')->nullable();
        });

        Schema::table('prenatal_consultation_details', function (Blueprint $table) {
            $table->string('status')->default('in queue');
            $table->timestamp('completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultation_details', function (Blueprint $table) {
            $table->dropColumn(['status', 'completed_at']);
        });

        Schema::table('prenatal_consultation_details', function (Blueprint $table) {
            $table->dropColumn(['status', 'completed_at']);
        });
    }
};
