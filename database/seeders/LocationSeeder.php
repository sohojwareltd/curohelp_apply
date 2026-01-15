<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $ukPath = resource_path('data/locations_uk.txt');
        $intlPath = resource_path('data/locations_international.txt');

        $ukList = File::exists($ukPath) ? $this->readList($ukPath) : [];
        $intlList = File::exists($intlPath) ? $this->readList($intlPath) : [];

        // Seed UK locations
        foreach ($ukList as $name) {
            $this->seedLocation($name, 'United Kingdom', 'UK', 'uk');
        }
        // Seed International locations (country unknown -> set as International)
        foreach ($intlList as $name) {
            $this->seedLocation($name, 'International', null, 'international');
        }
    }

    private function readList(string $path): array
    {
        return collect(preg_split('/\r?\n/', trim(File::get($path))))
            ->map(fn ($l) => trim($l))
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    private function seedLocation(string $name, ?string $country, ?string $region, string $type): void
    {
        $slug = Str::slug($name . '-' . ($region ?: $country ?: 'global'));
        Location::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'slug' => $slug,
                'country' => $country ?: 'International',
                'region' => $region,
                'type' => $type,
                'is_active' => true,
            ]
        );
    }
}
