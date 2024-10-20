<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckUp extends Model
{
    use HasFactory;
    protected $table = 'itr';

    protected $fillable = [
        'firstName', 'lastName', 'middleName', 'suffix', 'address',
        'age', 'birthdate', 'contact', 'sex', 'consultationDate',
        'consultationTime', 'modeOfTransaction', 'bloodPressure',
        'temperature', 'height', 'weight', 'providerName',
        'natureOfVisit', 'visitType', 'chiefComplaints',
        'diagnosis', 'medication'
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
