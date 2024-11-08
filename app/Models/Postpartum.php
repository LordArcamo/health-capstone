<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'lastName',
        'firstName',
        'middleName',
        'sex',
        'birthLength',
        'birthWeight',
        'prenatalDelivered',
        'placeDelivered',
        'modeOfDelivery',
        'attendantAtBirth',
        'deliveryDate',
        'deliveryTime',
        'dangerSignsMother',
        'dangerSignsBaby',
    ];

    // Hide timestamps from the array representation
    protected $hidden = ['created_at', 'updated_at'];
}
