<?php

namespace Database\Seeders;

use App\Models\JobRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Nanny', 'slug' => 'nanny', 'image_url' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=800&h=600&fit=crop'],
            ['name' => 'Butler', 'slug' => 'butler', 'image_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop'],
            ['name' => 'Private Chef', 'slug' => 'private-chef', 'image_url' => 'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?w=800&h=600&fit=crop'],
            ['name' => 'Chauffeur', 'slug' => 'chauffeur', 'image_url' => 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=800&h=600&fit=crop'],
            ['name' => 'Personal Assistant', 'slug' => 'personal-assistant', 'image_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&h=600&fit=crop'],
            ['name' => 'Estate Manager', 'slug' => 'estate-manager', 'image_url' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&h=600&fit=crop'],
            ['name' => 'Domestic Couple', 'slug' => 'domestic-couple', 'image_url' => 'https://images.unsplash.com/photo-1516589178581-6cd7833ae3b2?w=800&h=600&fit=crop'],
            ['name' => 'Gardener', 'slug' => 'gardener', 'image_url' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&h=600&fit=crop'],
            ['name' => 'Housekeeper', 'slug' => 'housekeeper', 'image_url' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=800&h=600&fit=crop'],
            ['name' => 'Bodyguard', 'slug' => 'bodyguard', 'image_url' => 'https://images.unsplash.com/photo-1550525811-e5869dd03032?w=800&h=600&fit=crop'],
            ['name' => 'Private Pilot', 'slug' => 'private-pilot', 'image_url' => 'https://images.unsplash.com/photo-1556388158-158ea5ccacbd?w=800&h=600&fit=crop'],
            ['name' => 'Governess', 'slug' => 'governess', 'image_url' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=600&fit=crop'],
            ['name' => 'Private Tutor', 'slug' => 'private-tutor', 'image_url' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&h=600&fit=crop'],
            ['name' => 'House Sitter', 'slug' => 'house-sitter', 'image_url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop'],
            ['name' => 'Yacht Captain', 'slug' => 'yacht-captain', 'image_url' => 'https://images.unsplash.com/photo-1540946485063-a40da27545f8?w=800&h=600&fit=crop'],
            ['name' => 'Private Driver', 'slug' => 'private-driver', 'image_url' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800&h=600&fit=crop'],
            ['name' => 'Security Guard', 'slug' => 'security-guard', 'image_url' => 'https://images.unsplash.com/photo-1542909168-82c3e7fdca5c?w=800&h=600&fit=crop'],
            ['name' => 'Maternity Nurse', 'slug' => 'maternity-nurse', 'image_url' => 'https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=800&h=600&fit=crop'],
            ['name' => 'Household Manager', 'slug' => 'household-manager', 'image_url' => 'https://images.unsplash.com/photo-1590650153855-d9e808231d41?w=800&h=600&fit=crop'],
            ['name' => 'Gamekeeper', 'slug' => 'gamekeeper', 'image_url' => 'https://images.unsplash.com/photo-1535083783855-76ae62b2914e?w=800&h=600&fit=crop'],
            ['name' => 'Groundsman', 'slug' => 'groundsman', 'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop'],
            ['name' => 'SEN Nanny', 'slug' => 'sen-nanny', 'image_url' => 'https://images.unsplash.com/photo-1503919545889-aef636e10ad4?w=800&h=600&fit=crop'],
            ['name' => 'Cleaners', 'slug' => 'cleaners', 'image_url' => 'https://images.unsplash.com/photo-1628177142898-93e36e4e3a50?w=800&h=600&fit=crop'],
            ['name' => 'Stable Manager', 'slug' => 'stable-manager', 'image_url' => 'https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=800&h=600&fit=crop'],
            ['name' => 'Doula', 'slug' => 'doula', 'image_url' => 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=800&h=600&fit=crop'],
            ['name' => 'Norland Nanny', 'slug' => 'norland-nanny', 'image_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=800&h=600&fit=crop'],
            ['name' => 'Mothers Helper', 'slug' => 'mothers-helper', 'image_url' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=800&h=600&fit=crop'],
            ['name' => 'Lifestyle Manager', 'slug' => 'lifestyle-manager', 'image_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=800&h=600&fit=crop'],
            ['name' => 'Close Protection Officer', 'slug' => 'close-protection-officer', 'image_url' => 'https://images.unsplash.com/photo-1566492031773-4f4e44671857?w=800&h=600&fit=crop'],
            ['name' => 'Chief Of Staff', 'slug' => 'chief-of-staff', 'image_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop'],
            ['name' => 'Property Maintenance Manager', 'slug' => 'property-maintenance-manager', 'image_url' => 'https://images.unsplash.com/photo-1581578949510-fa7315c4c350?w=800&h=600&fit=crop'],
            ['name' => 'Child Protection Officer', 'slug' => 'child-protection-officer', 'image_url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800&h=600&fit=crop'],
        ];

        foreach ($roles as $index => $role) {
            JobRole::updateOrCreate(
                ['slug' => $role['slug']],
                [
                    'name' => $role['name'],
                    'description' => $role['description'] ?? ('Experienced '.$role['name'].' for UHNW households'),
                    'image_url' => $role['image_url'] ?? null,
                    'order' => $index,
                    'is_active' => true,
                    'slug' => $role['slug'] ?? Str::slug($role['name']),
                ]
            );
        }
    }
}
