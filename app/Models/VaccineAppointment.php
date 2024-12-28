<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VaccineAppointment extends Model
{
    use HasFactory;

    protected $table = 'vaccine_appointments';

    protected $primaryKey = 'appointmentId';

    protected $fillable = [
        'personalId',
        'appointmentDate',
        'vaccineType',
        'status', // scheduled, completed, cancelled
        'notes',
        'createdBy',
        'updatedBy'
    ];

    protected $casts = [
        'appointmentDate' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with PersonalInformation
    public function patient()
    {
        return $this->belongsTo(PersonalInformation::class, 'personalId', 'personalId');
    }
}
