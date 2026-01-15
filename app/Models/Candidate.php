<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'full_name',
        'last_name',
        'email',
        'phone',
        'postcode',
        'nearest_city',
        'country',
        'lat',
        'lng',
        'driving_licence',
        'own_car',
        'valid_passport',
        'status',
    ];

    protected $casts = [
        'driving_licence' => 'boolean',
        'own_car' => 'boolean',
        'valid_passport' => 'boolean',
        'lat' => 'float',
        'lng' => 'float',
    ];

    /**
     * Override toArray to ensure all string values are valid UTF-8.
     */
    public function toArray()
    {
        $array = parent::toArray();
        return $this->sanitizeUtf8($array);
    }

    /**
     * Sanitize all string values to ensure valid UTF-8 encoding.
     */
    private function sanitizeUtf8($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = $this->sanitizeUtf8($value);
                } elseif (is_string($value)) {
                    // Remove invalid UTF-8 characters
                    $data[$key] = $this->fixUtf8String($value);
                }
            }
        } elseif (is_string($data)) {
            $data = $this->fixUtf8String($data);
        }
        return $data;
    }

    /**
     * Fix invalid UTF-8 characters in a string.
     * This version actually strips invalid bytes instead of just validating.
     */
    private function fixUtf8String(string $value): string
    {
        // Method 1: Convert from UTF-8 (auto-detect) back to UTF-8, ignoring errors
        // This strips invalid byte sequences
        $fixed = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
        
        // If iconv fails, fall back to mb_convert_encoding with error handling
        if ($fixed === false) {
            $fixed = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }
        
        return $fixed ?? $value;
    }

    /**
     * Scope candidates within a radius (km) of a point using the Haversine formula.
     */
    public function scopeWithinRadius(Builder $query, float $lat, float $lng, float $radiusInKm = 20): Builder
    {
        $haversine = '6371 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat)))';

        return $query
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->select('*')
            ->selectRaw("{$haversine} as distance", [$lat, $lng, $lat])
            ->having('distance', '<=', $radiusInKm)
            ->orderBy('distance');
    }

    /**
     * Scope candidates where distance from point is between min and max km.
     * If only $minKm is provided, filters >= min. If only $maxKm, filters <= max.
     */
    public function scopeWithinRadiusBetween(Builder $query, float $lat, float $lng, ?float $minKm, ?float $maxKm): Builder
    {
        $haversine = '6371 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat)))';

        $query = $query
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->select('*')
            ->selectRaw("{$haversine} as distance", [$lat, $lng, $lat]);

        if ($minKm !== null && $maxKm !== null) {
            $query->havingRaw('distance BETWEEN ? AND ?', [$minKm, $maxKm]);
        } elseif ($minKm !== null) {
            $query->having('distance', '>=', $minKm);
        } elseif ($maxKm !== null) {
            $query->having('distance', '<=', $maxKm);
        }

        return $query->orderBy('distance');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(CandidateProfile::class);
    }

    public function preferences(): HasOne
    {
        return $this->hasOne(CandidatePreference::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function jobRoles(): BelongsToMany
    {
        return $this->belongsToMany(JobRole::class, 'candidate_job_role')->withTimestamps();
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class)->withTimestamps();
    }

    public function countryRelation(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country', 'code');
    }

    public function country2Relation(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_2', 'code');
    }

    public function files(): HasMany
    {
        return $this->hasMany(CandidateFile::class);
    }
}
