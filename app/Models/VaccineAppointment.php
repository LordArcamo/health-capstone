<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VaccineAppointment extends Model
{
    use HasFactory;

    protected $table = 'vaccine_appointments';

    protected $primaryKey = 'vacAppointmentId';

    protected $fillable = [
        'vaccinationId',
        'dateOfVisit',
        'weight',
        'height',
        'temperature',
        'antigenGiven',
        'injectedBy',
        'nextAppointment',
        'exclusivelyBreastfed'
    ];

    protected $casts = [
        'dateOfVisit' => 'date',
        'nextAppointment' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'temperature' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the vaccination record that this appointment belongs to
     */
    public function vaccinationRecord(): BelongsTo
    {
        return $this->belongsTo(VaccinationRecord::class, 'vaccinationId');
    }

    /**
     * Get the personal information through the vaccination record
     */
    public function personalInformation()
    {
        return $this->vaccinationRecord->personalInformation();
    }
}
