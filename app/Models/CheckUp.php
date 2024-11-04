<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckUp extends Model
{
    use HasFactory;
    
    protected $table = 'itr';

    // Specify the primary key
    protected $primaryKey = 'itrId';

    // Set the primary key type
    protected $keyType = 'int';
    
    // Ensure the primary key is auto-incrementing
    public $incrementing = true;

    // Define fillable properties, including the foreign key
    protected $fillable = [
        'personalId', // Foreign key linking to personal_information
        'consultationDate',
        'consultationTime',
        'modeOfTransaction',
        'bloodPressure',
        'temperature',
        'height',
        'weight',
        'providerName',
        'natureOfVisit',
        'visitType',
        'chiefComplaints',
        'diagnosis',
        'medication'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    // Define relationship to PersonalInformation
    public function personalInformation()
    {
        return $this->belongsTo(PersonalInformation::class, 'personalId');
    }
}
