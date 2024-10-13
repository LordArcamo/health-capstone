<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    use HasFactory;
    protected $table = 'personal_information';

    protected $fillable = [
        'firstName', 'lastName', 'middleName', 'suffix', 'address',
        'age', 'birthdate', 'contact', 'sex'
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
