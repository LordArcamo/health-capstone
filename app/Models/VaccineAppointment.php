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
        'appointmentDate',
        'status',
        'notes'
    ];

    protected $casts = [
        'appointmentDate' => 'datetime',
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
