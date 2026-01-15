<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidatePreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'pets_preference',
        'smokers_preference',
        'living_arrangement',
        'unwanted',
        'employment_type',
        'working_hours',
        'salary_expectation_annual',
        'salary_expectation_hourly',
        'key_skills',
        'qualifications',
        'training_completed',
        'duties_performed',
        'personal_qualities',
    ];

    protected $casts = [
        'working_hours' => 'array',
        'qualifications' => 'array',
        'training_completed' => 'array',
        'duties_performed' => 'array',
        'personal_qualities' => 'array',
        'salary_expectation_annual' => 'decimal:2',
        'salary_expectation_hourly' => 'decimal:2',
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
     */
    private function fixUtf8String(string $value): string
    {
        $fixed = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
        if ($fixed === false) {
            $fixed = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }
        return $fixed ?? $value;
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
