<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booth extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'number',
        'size',
        'type',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'integer',
    ];
}
