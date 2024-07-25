<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckUp extends Model
{
    use HasFactory;

    protected $table = 'check_ups';

    protected $fillable = [
        'fname',
        'lname',
        'age',
        'sex'
    ];
}
