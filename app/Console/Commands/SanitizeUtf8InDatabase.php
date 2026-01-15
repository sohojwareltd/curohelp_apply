<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Candidate;
use App\Models\CandidateProfile;
use App\Models\CandidatePreference;
use Illuminate\Support\Facades\DB;

class SanitizeUtf8InDatabase extends Command
{
    protected $signature = 'db:sanitize-utf8';
    protected $description = 'Sanitize UTF-8 characters in database records';

    public function handle()
    {
        $this->info('Sanitizing UTF-8 characters in database...');

        // Sanitize candidates table
        $this->info('Processing candidates table...');
        $this->sanitizeTable('candidates');

        // Sanitize candidate_profiles table
        $this->info('Processing candidate_profiles table...');
        $this->sanitizeTable('candidate_profiles');

        // Sanitize candidate_preferences table
        $this->info('Processing candidate_preferences table...');
        $this->sanitizeTable('candidate_preferences');

        $this->info('UTF-8 sanitization complete!');
    }

    /**
     * Sanitize UTF-8 characters in a table.
     */
    private function sanitizeTable(string $tableName)
    {
        DB::table($tableName)->get()->each(function ($row, $index) use ($tableName) {
            $updates = [];
            foreach ((array) $row as $key => $value) {
                if (is_string($value)) {
                    $sanitized = $this->fixUtf8String($value);
                    if ($sanitized !== $value) {
                        $updates[$key] = $sanitized;
                    }
                }
            }
            if (!empty($updates)) {
                DB::table($tableName)->where('id', $row->id)->update($updates);
                $this->line("  Updated {$tableName} ID: {$row->id}");
            }
        });
    }

    /**
     * Fix invalid UTF-8 characters in a string.
     * This actually strips invalid bytes instead of just validating.
     */
    private function fixUtf8String(string $value): string
    {
        // Use iconv with IGNORE flag to strip invalid bytes
        $fixed = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
        
        // If iconv fails, fall back to mb_convert_encoding
        if ($fixed === false) {
            $fixed = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }
        
        return $fixed ?? $value;
    }
}
