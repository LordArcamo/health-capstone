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
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing name column
            $table->dropColumn('name');
            
            // Add new name fields
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            
            // Add address fields
            $table->string('purok');
            $table->string('barangay');
            $table->string('city');
            
            // Add contact info
            $table->string('phone', 13);
            
            // Add profile picture
            $table->string('profile_picture')->nullable();
            
            // Add permissions
            $table->json('permissions')->nullable();
            
            // Add doctor-specific fields
            $table->string('prc_number')->nullable();
            $table->string('specialization')->nullable();
            $table->date('prc_validity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore the original name column
            $table->string('name');
            
            // Remove the new columns
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'purok',
                'barangay',
                'city',
                'phone',
                'profile_picture',
                'permissions',
                'prc_number',
                'specialization',
                'prc_validity',
            ]);
        });
    }
};
