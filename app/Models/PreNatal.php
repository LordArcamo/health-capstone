<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreNatal extends Model
{
    use HasFactory;
    protected $table = 'pre_natals';

    protected $fillable = [
        'firstName', 'lastName', 'middleName', 'address', 'age', 'birthdate',
        'modeOfTransaction', 'consultationDate', 'consultationTime', 'bloodPressure',
        'temperature', 'height', 'weight', 'providerName', 'nameOfSpouse',
        'emergencyContact', 'fourMember', 'philhealthStatus', 'philhealthId',
        'menarche', 'sexualOnset', 'periodDuration', 'birthControl', 'intervalCycle',
        'menopause', 'lmp', 'edc', 'gravidity', 'parity', 'term', 'preterm',
        'abortion', 'living', 'syphilisResult', 'penicillin', 'hemoglobin',
        'hematocrit', 'urinalysis', 'ttStatus', 'tdDate',
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
