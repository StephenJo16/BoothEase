<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name', // Dari form: business_name
        'display_name', // Dari form: full_name
        'email',
        'phone_number', // Gabungan country_code + phone_number
        'business_category',
        'password',
        'provider',
        'provider_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the ratings given by this user.
     */
    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    /**
     * Get the ratings received by this user.
     */
    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'ratee_id');
    }
}
