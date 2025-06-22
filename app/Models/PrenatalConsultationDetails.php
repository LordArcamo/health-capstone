<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrenatalConsultationDetails extends Model
{
    use HasFactory;

    protected $table = 'prenatal_consultation_details';

    protected $primaryKey = 'prenatalConsultationDetailsID'; // Set the primary key

    public $incrementing = true; // Indicates that the ID is incrementing
    protected $keyType = 'int'; // Set the key type

    protected $fillable = [
        'personalId', 'id',
        'modeOfTransaction', 'consultationDate', 'consultationTime', 'bloodPressure',
        'temperature', 'height', 'weight', 'providerName', 'nameOfSpouse',
        'emergencyContact', 'fourMember', 'philhealthStatus', 'philhealthNo',
        'status',
        'completed_at',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    // Define the relationship with the PersonalInformation model
    public function personalInformation()
    {
        return $this->belongsTo(PersonalInformation::class, 'personalId');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
