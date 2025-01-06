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
        'prenatalId',
        'id', // Foreign key for users table
        'lastName',
        'firstName',
        'middleName',
        'sex',
        'prenatalDelivered',
        'placeDelivered',
        'modeOfDelivery',
        'birthLength',
        'birthWeight',
        'deliveryDate',
        'deliveryTime',
        'attendantBirth',
        'dateInitiatedBreastfeeding',
        'timeInitiatedBreastfeeding',
        'dateOfPostpartumVisitTwentyFourHoursDelivery',
        'dateOfPostpartumVisitOneWeekDelivery',
        'dateVitaminA',
        'dateIronGiven',
        'noIronGiven',
        'dangerSignsMother',
        'dangerSignsBaby',
    ];

    // Hide timestamps from the array representation
    protected $hidden = ['created_at', 'updated_at'];

    // Define attribute casting
    // protected $casts = [
    //     'birthLength' => 'decimal:2',
    //     'birthWeight' => 'decimal:2',
    //     'noIronGiven' => 'decimal:2',
    //     'deliveryDate' => 'date',
    //     'deliveryTime' => 'datetime',
    //     'dateInitiatedBreastfeeding' => 'date',
    //     'timeInitiatedBreastfeeding' => 'datetime',
    //     'dateOfPostpartumVisitTwentyFourHoursDelivery' => 'date',
    //     'dateOfPostpartumVisitOneWeekDelivery' => 'date',
    //     'dateVitaminA' => 'date',
    //     'dateIronGiven' => 'date',
    // ];

    // Define relationship with Prenatal model
    public function prenatal()
    {
        return $this->belongsTo(PreNatal::class, 'prenatalId', 'prenatalId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
