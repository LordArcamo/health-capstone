<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckBox1 extends Model
{
    // Set the table name if it doesn't follow Laravel's naming convention
    protected $table = 'checkbox1';

    // Set the primary key to 'checkbox1ID'
    protected $primaryKey = 'checkbox1ID';

    protected $keyType = 'int';

    // Disable auto-incrementing since you're using a big integer as the primary key
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

    // Define the relationship with the GeneralTrimester model
    public function generalTrimester()
    {
        return $this->belongsTo(GeneralTrimester::class, 'generalTrimesterID', 'generalTrimesterID');
    }
}
