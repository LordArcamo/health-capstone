<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckBox1 extends Model
{
    // Set the table name if it doesn't follow Laravel's naming convention
    protected $table = 'checkbox1';

    // Set the primary key to 'checkbox1ID'
    protected $primaryKey = 'checkbox1ID';

    // Define the key type (for big integer primary key)
    protected $keyType = 'int';

    // Enable auto-incrementing since the primary key is an auto-incrementing big integer
    public $incrementing = true;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'generalTrimesterID',
        'prenatal_checkup',
        'pe_done',
        'prenatal_record',
        'birth_plan_done',
        'nkfda',
        'health_teachings',
        'referred_for',
        'healthy_diet',
        'fes04_folic',
        'folic_acid',
        'fhb',
        'position',
        'presentation',
        'fundal_height',
    ];

    // Automatically cast boolean fields to true/false when retrieving from or saving to the database
    protected $casts = [
        'prenatal_checkup' => 'boolean',
        'pe_done' => 'boolean',
        'prenatal_record' => 'boolean',
        'birth_plan_done' => 'boolean',
        'nkfda' => 'boolean',
        'health_teachings' => 'boolean',
        'referred_for' => 'boolean',
        'healthy_diet' => 'boolean',
        'fes04_folic' => 'boolean',
    ];

    // Define the relationship with the GeneralTrimester model
    public function generalTrimester()
    {
        return $this->belongsTo(GeneralTrimester::class, 'generalTrimesterID', 'generalTrimesterID');
    }
}
