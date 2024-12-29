<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;


class RiskManagement extends Model
{
    protected $table = 'risk_management';
    
    // Set the primary key to immunizationID
    protected $primaryKey = 'riskId';
    
    // Indicate if the IDs are not auto-incrementing integers
    public $incrementing = true;
    
    // Define the primary key data type as integer
    protected $keyType = 'int';

    protected $fillable = [
        'personalId',
        'foodIntake',
        'physicalActivity',
        'bloodGlucose',
        'fbsRbs',
        'bloodGlucoseDate',
        'bloodLipids',
        'totalCholesterol',
        'bloodLipidsDate',
        'urineKetones',
        'urineKetoneLevel',
        'urineKetonesDate',
        'urineProtein',
        'urineProteinLevel',
        'urineProteinDate',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(PersonalInformation::class, 'personalId');
    }
}
