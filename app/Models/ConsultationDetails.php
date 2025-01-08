<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationDetails extends Model
{
    use HasFactory;

    protected $table = 'consultation_details';

    // Specify the primary key
    protected $primaryKey = 'consultationDetailsID';

    // Set the primary key type
    protected $keyType = 'int';

    // Ensure the primary key is auto-incrementing
    public $incrementing = true;

    // Define fillable properties, including the foreign key
    protected $fillable = [
        'personalId', // Foreign key linking to personal_information
        'id',
        'consultationDate',
        'consultationTime',
        'modeOfTransaction',
        'bloodPressure',
        'temperature',
        'height',
        'weight',
        'referredFrom',
        'referredTo',
        'reasonsForReferral',
        'referredBy',
        'natureOfVisit',
        'visitType',
        'providerName',

    ];

    protected $hidden = ['created_at', 'updated_at'];

    // Define relationship to PersonalInformation
    public function personalInformation()
    {
        return $this->belongsTo(PersonalInformation::class, 'personalId');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function visitInformation()
    {
        return $this->hasOne(VisitInformation::class, 'consultationDetailsID', 'consultationDetailsID');
    }
}
