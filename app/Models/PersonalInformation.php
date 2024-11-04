<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $hidden = ['created_at', 'updated_at'];
}
