<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VisitInformation extends Model
{
    use HasFactory;

    protected $table = 'visit_information';

    // Specify the primary key
    protected $primaryKey = 'visitInformationID';

    // Set the primary key type
    protected $keyType = 'int';

    // Ensure the primary key is auto-incrementing
    public $incrementing = true;

    // Define fillable properties, including the foreign key
    protected $fillable = [
        'consultationDetailsID',
        'id',
        'chiefComplaints',
        'diagnosis',
        'medication',
        'requireLabTest',
        'selectedLabTests',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    // Define relationship to PersonalInformation
    public function consultationDetails()
    {
        return $this->belongsTo(ConsultationDetails::class, 'consultationDetailsID');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
