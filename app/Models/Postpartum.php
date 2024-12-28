<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Prenatal;

class Postpartum extends Model
{
    use HasFactory;

    protected $table = 'postpartum';

    // Specify the primary key
    protected $primaryKey = 'postpartumId';

    // Ensure that it increments automatically
    public $incrementing = true;

    // Set the primary key type if needed (usually 'int')
    protected $keyType = 'int';

    // Add the fillable properties
    protected $fillable = [
        'prenatalId',
        'lastName',
        'firstName',
        'middleName',
        'sex',
        'birthLength',
        'birthWeight',
        'deliveryDate',
        'deliveryTime',
        'dateInitiatedBreastfeeding',
        'timeInitiatedBreastfeeding',
        'dateVitaminA',
        'dangerSignsMother'
    ];

    // Hide timestamps from the array representation
    protected $hidden = ['created_at', 'updated_at'];

    // Define relationship with Prenatal model
    public function prenatal()
    {
        return $this->belongsTo(Prenatal::class, 'prenatalId', 'prenatalId');
    }
}
