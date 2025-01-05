<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalImmunizationProgram extends Model
{
    use HasFactory;

    protected $table = 'national_immunization_programs';
    
    // Set the primary key to immunizationID
    protected $primaryKey = 'immunizationId';
    
    // Indicate if the IDs are not auto-incrementing integers
    public $incrementing = true;
    
    // Define the primary key data type as integer
    protected $keyType = 'int';

    protected $fillable = [
        'personalId',
        'id',
        'birthplace',
        'bloodtype',
        'mothername',
        'dswdNhts',
        'facilityHouseholdno',
        'houseHoldno',
        'fourpsmember',
        'PCBMember',
        'philhealthStatus',
        'philhealthNo',
        'ifMember',
        'familyMember',
        'ttStatus',
        'dateAssesed',
        'date',
        'place',
        'guardian',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Define the relationship with the PersonalInformation model.
     */
    public function personalInformation()
    {
        return $this->belongsTo(PersonalInformation::class, 'personalId');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
