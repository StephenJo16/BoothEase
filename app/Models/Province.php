<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';

    protected $fillable = [
        'code',
        'name',
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
