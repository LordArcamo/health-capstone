<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckBox2 extends Model
{
    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'checkbox2';

    // Specify the primary key
    protected $primaryKey = 'checkbox2ID';

    // Set the key type
    protected $keyType = 'int';

    // Enable auto-incrementing for the primary key
    public $incrementing = true;

    // Allow mass assignment for these attributes
    protected $fillable = [
        'generalTrimesterID',
        'prenatal_record',
        'reminded_importance',
        'health_teachings',
        'reminded_dangers',
        'healthy_diet',
        'breast_feeding',
        'compliane_routine',
        'referred_utz',
        'fes04_folic',
        'folic_acid',
    ];

    // Cast boolean fields
    protected $casts = [
        'prenatal_record' => 'boolean',
        'reminded_importance' => 'boolean',
        'health_teachings' => 'boolean',
        'reminded_dangers' => 'boolean',
        'healthy_diet' => 'boolean',
        'breast_feeding' => 'boolean',
        'compliane_routine' => 'boolean',
        'referred_utz' => 'boolean',
        'fes04_folic' => 'boolean',
    ];

    // Define the relationship with the GeneralTrimester model
    public function generalTrimester()
    {
        return $this->belongsTo(GeneralTrimester::class, 'generalTrimesterID', 'generalTrimesterID');
    }
}
