<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'date', 'status',  // Add all columns from your 'sessions' table here
    ];

    // Optionally, if you want to specify the table name (if not following the convention):
    // protected $table = 'sessions';
}
