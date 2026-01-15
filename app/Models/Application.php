<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'uuid',
        // Step 1: Personal Details
        'full_name',
        'email',
        'phone',
        'postcode',
        'nearest_city',
        'driving_licence',
        'own_car',

        // Step 2: Roles Wanted
        'roles',

        // Step 3: Preferences
        'pets_preference',
        'smokers_preference',
        'living_arrangement',
        'unwanted',

        // Step 4: Locations
        'locations',

        // Step 5: Remuneration
        'employment_type',
        'working_hours',
        'salary_expectation_annual',
        'salary_expectation_hourly',

        // Step 6: Profile
        'overview',
        'skills',
        'qualifications',
        'training',
        'duties_performed',
        'personal_qualities',

        // Step 7: Documents
        'photo',
        'intro_video',
        'cv',
        'certificates',
        'training_documents',
        'security_checks',

        // System
        'user_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'roles' => 'array',
        'locations' => 'array',
        'working_hours' => 'array',
        'driving_licence' => 'boolean',
        'own_car' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
