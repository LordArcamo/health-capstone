<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckUp extends Model
{
    use HasFactory;

    protected $table = 'check_ups';

    protected $fillable = [
        'fname',
        'lname',
        'mname',
        'suffix',
        'address',
        'age',
        'dob',
        'contact_num',
        'sex',
        'date_of_consult',
        'time_of_consult',
        'bp',
        'height',
        'weight',
        'temperature',
        'attendname',
        'nature_visit',
        'type_consult',
        'diagnosis',
        'treatment',
        'nurse',
    ];
}
