<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $table = 'prescriptions';

    // Specify the primary key
    protected $primaryKey = 'prescriptionID';

    // Set the primary key type
    protected $keyType = 'int';

    // Ensure the primary key is auto-incrementing
    public $incrementing = true;

    // Define fillable properties, including the foreign key
    protected $fillable = [
        'visitInformationID',
        'medication',
        'dosage',
        'frequency',
        'duration',
        'notes',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    // Define relationship to PersonalInformation
    public function visitInformation()
    {
        return $this->belongsTo(VisitInformation::class, 'visitInformationID');
    }
}
