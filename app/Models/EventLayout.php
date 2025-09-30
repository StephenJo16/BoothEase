<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'layout_json',
        'booth_count',
    ];

    protected $casts = [
        'layout_json' => 'array',
    ];
}