<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreNatal extends Model
{
    use HasFactory;

    protected $table = 'prenatal';

    protected $primaryKey = 'prenatalId'; // Set the primary key

    public $incrementing = true; // Indicates that the ID is incrementing
    protected $keyType = 'int'; // Set the key type

    protected $fillable = [
        'personalId', // Added personalId as a fillable field
        'modeOfTransaction', 'consultationDate', 'consultationTime', 'bloodPressure',
        'temperature', 'height', 'weight', 'providerName', 'nameOfSpouse',
        'emergencyContact', 'fourMember', 'philhealthStatus', 'philhealthNo',
        'menarche', 'sexualOnset', 'periodDuration', 'birthControl', 'intervalCycle',
        'menopause', 'lmp', 'edc', 'gravidity', 'parity', 'term', 'preterm',
        'abortion', 'living', 'syphilisResult', 'penicillin', 'hemoglobin',
        'hematocrit', 'urinalysis', 'ttStatus', 'tdDate',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    // Define the relationship with the PersonalInformation model
    public function personalInformation()
    {
        return $this->belongsTo(PersonalInformation::class, 'personalId', 'personalId');
    }
}
