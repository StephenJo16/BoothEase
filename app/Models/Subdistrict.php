<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subdistrict extends Model
{
    use HasFactory;

    protected $table = 'subdistricts';

    protected $fillable = [
        'district_id',
        'code',
        'name',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
