<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'job_role_id',
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'profile_image',
        'bio',
        'years_of_experience',
        'hourly_rate',
        'skills',
        'languages',
        'linkedin_url',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'is_active',
        'is_available',
        'is_featured',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hourly_rate' => 'decimal:2',
        'skills' => 'array',
        'languages' => 'array',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobRole(): BelongsTo
    {
        return $this->belongsTo(JobRole::class);
    }
}
