<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrenatalVisitInformation extends Model
{
    use HasFactory;

    protected $table = 'prenatal_visit_information';

    protected $primaryKey = 'prenatalVisitInformationID'; // Set the primary key

    public $incrementing = true; // Indicates that the ID is incrementing
    protected $keyType = 'int'; // Set the key type

    protected $fillable = [
        'prenatalConsultationDetailsID', 'id',
        'menarche', 'sexualOnset', 'periodDuration', 'birthControl', 'intervalCycle',
        'menopause', 'lmp', 'edc', 'gravidity', 'parity', 'term', 'preterm',
        'abortion', 'living', 'syphilisResult', 'penicillin', 'hemoglobin',
        'hematocrit', 'urinalysis', 'ttStatus', 'tdDate',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function prenatalConsultationDetails()
    {
        return $this->belongsTo(PrenatalConsultationDetails::class, 'prenatalConsultationDetailsID', 'prenatalConsultationDetailsID');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
