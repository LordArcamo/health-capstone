<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalImmunizationProgram extends Model
{
    use HasFactory;
    protected $table = 'national_immunization_programs';

    protected $fillable = [

        'firstName', 'lastName', 'middleName', 'suffix', 'address',
        'age', 'birthdate', 'contact', 'sex',
        'birthplace', 'bloodtype', 'mothername', 'dswdNhts', 'facilityHouseholdno', 'houseHoldno',
        'fourpsmember', 'PCBMember', 'philhealthMember', 'statusType', 'philhealthNo', 'ifMember', 
        'familyMember', 'ttstatus', 'dateAssesed', 'date', 'place', 'guardian'
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
