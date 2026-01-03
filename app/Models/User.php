<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'category_id',
        'name', // Dari form: business_name
        'display_name', // Dari form: full_name
        'email',
        'email_verified_at',
        'phone_number', // Gabungan country_code + phone_number
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
     * Get the category associated with the user.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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

    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at) || ! is_null($this->provider);
    }

    /**
     * Check if user has completed their profile.
     * For OAuth users, phone_number and category_id must be filled.
     */
    public function hasCompletedProfile(): bool
    {
        // If user signed up via OAuth (has provider), check if profile is complete
        if ($this->provider) {
            return !empty($this->phone_number) && !empty($this->category_id);
        }

        // Non-OAuth users are considered complete by default
        return true;
    }
}
