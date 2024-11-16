<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkbox3 extends Model
{
    use HasFactory;

    // Specify the table name if it does not match the default naming convention
    protected $table = 'checkbox3';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'checkbox3ID';

    protected $keyType = 'int';

    // Enable auto-incrementing for the primary key
    public $incrementing = true;

    // Allow mass assignment for these fields
    protected $fillable = [
        'generalTrimesterID',
        'trimester_type',
        'prenatal_checkup',
        'pe_done',
        'prenatal_record',
        'reminded_importance',
        'health_teachings',
        'reminded_dangers',
        'healthy_diet',
        'breast_feeding',
        'compliance_routine',
        'referred_utz',
        'information_newborn',
        'fes04_folic',
        'information_family',
        'folic_acid',
        'fhb',
        'position',
        'presentation',
        'fundal_height',
    ];

    /**
     * Attribute casting to automatically handle data types.
     */
    protected $casts = [
        'prenatal_checkup' => 'boolean',
        'pe_done' => 'boolean',
        'prenatal_record' => 'boolean',
        'reminded_importance' => 'boolean',
        'health_teachings' => 'boolean',
        'reminded_dangers' => 'boolean',
        'healthy_diet' => 'boolean',
        'breast_feeding' => 'boolean',
        'compliance_routine' => 'boolean',
        'referred_utz' => 'boolean',
        'information_newborn' => 'boolean',
        'fes04_folic' => 'boolean',
        'information_family' => 'boolean',
    ];

    /**
     * Define the relationship to the `GeneralTrimester` model.
     * Assuming the `GeneralTrimester` model exists.
     */
    public function generalTrimester()
    {
        return $this->belongsTo(GeneralTrimester::class, 'generalTrimesterID', 'generalTrimesterID');
    }
}
