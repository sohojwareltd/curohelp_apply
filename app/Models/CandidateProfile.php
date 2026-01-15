<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'overview',
        'skills',
        'qualifications',
        'training',
        'duties_performed',
        'personal_qualities',
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
