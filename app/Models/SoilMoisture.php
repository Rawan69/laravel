<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoilMoisture extends Model
{
    use HasFactory;

    protected $fillable = ['is_moist'];

    protected $casts = [
        'is_moist' => 'boolean',
    ];
}

