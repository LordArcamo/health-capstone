<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'phone',
        'purok',
        'barangay',
        'city',
        'profile_picture',
        'permissions',
        'prc_number',
        'specialization',
        'prc_validity',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'permissions' => 'json',
        'prc_validity' => 'date',
    ];

    protected $attributes = [
        'first_name' => '',
        'middle_name' => '',
        'last_name' => '',
        'specialization' => '',
        'prc_number' => '',
        'purok' => '',
        'barangay' => '',
        'city' => '',
        'phone' => '',
        'role' => 'user',
    ];
}
