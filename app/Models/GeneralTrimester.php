<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Checkbox1;
use App\Models\CheckBox2;
use App\Models\Checkbox3;

class GeneralTrimester extends Model
{
    // Define the table name
    protected $table = 'general_trimester';

    // Set the primary key field
    protected $primaryKey = 'generalTrimesterID';

    // Disable the auto-incrementing feature as we're using a custom primary key
    public $incrementing = true;
    protected $keyType = 'int';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'prenatalId',
        'date_of_visit',
        'weight',
        'bp',
        'heart_rate',
        'aog_months',
        'aog_days',
        'trimester',
    ];

    // Define the relationship with the Prenatal model
    public function prenatal()
    {
        return $this->belongsTo(Prenatal::class, 'prenatalId', 'prenatalId');
    }
    public function checkbox1()
    {
        return $this->hasOne(Checkbox1::class, 'generalTrimesterID', 'generalTrimesterID');
    }

    public function checkbox2()
    {
        return $this->hasOne(CheckBox2::class, 'generalTrimesterID', 'generalTrimesterID');
    }

    public function checkbox3()
    {
        return $this->hasOne(Checkbox3::class, 'generalTrimesterID', 'generalTrimesterID');
    }
}
