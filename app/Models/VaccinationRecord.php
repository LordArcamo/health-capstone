<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VaccinationRecord extends Model
{
    protected $table = 'vaccination_records';

    protected $primaryKey = 'vaccinationId';

    // Set the primary key type
    protected $keyType = 'int';
    
    // Ensure the primary key is auto-incrementing
    public $incrementing = true;

    protected $fillable = [
        'personalId',
        'id',
        'vaccineCategory',
        'vaccineType',
        'dateOfVisit',
        'ageInMonths',
        'ageInYears',
        'weight',
        'height',
        'temperature',
        'antigenGiven',
        'injectedBy',
        'exclusivelyBreastfed',
        'nextAppointment'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'dateOfVisit' => 'date',
        'nextAppointment' => 'date',
        'ageInMonths' => 'integer',
        'ageInYears' => 'integer',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'temperature' => 'decimal:1'
    ];

    /**
     * Get the patient that owns this vaccination record.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(PersonalInformation::class, 'personalId');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
