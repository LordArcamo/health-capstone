<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentalHealthSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'status'
    ];

    protected $casts = [
        'date' => 'date:d-m-Y',
    ];
}
