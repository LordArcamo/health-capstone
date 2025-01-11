<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VaccinationRecord;
use App\Models\RiskManagement;

class PersonalInformation extends Model
{
    use HasFactory;

    protected $table = 'personal_information';

    // Specify the primary key
    protected $primaryKey = 'personalId';

    // Ensure that it increments automatically
    public $incrementing = true;

    // Set the primary key type if needed (usually 'int')
    protected $keyType = 'int';

    // Add the fillable properties
    protected $fillable = [
        'firstName', 'lastName', 'middleName', 'suffix', 'purok', 'barangay',
        'age', 'birthdate', 'contact', 'sex'
    ];

    /**
     * Get the vaccination records for the patient.
     */
    public function vaccinationRecords()
    {
        return $this->hasMany(VaccinationRecord::class, 'personalId', 'personalId');
    }
    public function riskManagementRecords()
    {
        return $this->hasMany(RiskManagement::class, 'personalId', 'personalId');
    }
    public function consultations()
    {
        return $this->hasMany(ConsultationDetail::class, 'personalId', 'personalId');
    }
    public function consultationDetails()
    {
        return $this->hasMany(ConsultationDetails::class, 'personalId', 'personalId');
    }
    public function prenatalConsultationDetails()
    {
        return $this->hasMany(PrenatalConsultationDetails::class, 'personalId', 'personalId');
    }

    public function nationalImmunizationPrograms()
    {
        return $this->hasMany(NationalImmunizationProgram::class, 'personalId', 'personalId');
    }
}
