<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';

    protected $fillable = [
        'city_id',
        'code',
        'name',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function subdistricts(): HasMany
    {
        return $this->hasMany(Subdistrict::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
