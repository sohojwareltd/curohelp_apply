<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\JobRole;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $jobRoles = JobRole::all();

        $employees = [
            [
                'name' => 'Sarah Thompson',
                'email' => 'sarah.thompson@curohelp.com',
                'phone' => '+1 (555) 123-4567',
                'gender' => 'female',
                'job_role_slug' => 'nanny',
                'city' => 'London',
                'country' => 'United Kingdom',
                'bio' => 'Experienced Norland-trained nanny with 12+ years caring for UHNW families.',
                'years_of_experience' => 12,
                'hourly_rate' => 45.00,
                'skills' => ['Child Development', 'Early Education', 'First Aid', 'Nutrition'],
                'languages' => ['English', 'French'],
                'profile_image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=400&fit=crop',
                'is_featured' => true,
            ],
            [
                'name' => 'James Morrison',
                'email' => 'james.morrison@curohelp.com',
                'phone' => '+44 20 7123 4567',
                'gender' => 'male',
                'job_role_slug' => 'butler',
                'city' => 'Monaco',
                'country' => 'Monaco',
                'bio' => 'Classically trained butler with expertise in fine service and estate management.',
                'years_of_experience' => 18,
                'hourly_rate' => 55.00,
                'skills' => ['Fine Dining Service', 'Wine Sommelier', 'Event Planning', 'Staff Management'],
                'languages' => ['English', 'French', 'Italian'],
                'linkedin_url' => 'https://linkedin.com/in/jamesmorrison',
                'profile_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop',
                'is_featured' => true,
            ],
            [
                'name' => 'Maria Rodriguez',
                'email' => 'maria.rodriguez@curohelp.com',
                'phone' => '+33 1 42 86 82 00',
                'gender' => 'female',
                'job_role_slug' => 'private-chef',
                'city' => 'Paris',
                'country' => 'France',
                'bio' => 'Michelin-trained chef specializing in fine dining and dietary requirements.',
                'years_of_experience' => 15,
                'hourly_rate' => 65.00,
                'skills' => ['Fine Dining', 'Molecular Gastronomy', 'Dietary Planning', 'Menu Design'],
                'languages' => ['Spanish', 'French', 'English'],
                'instagram_url' => 'https://instagram.com/chefmaria',
                'profile_image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&h=400&fit=crop',
                'is_featured' => true,
            ],
            [
                'name' => 'David Chen',
                'email' => 'david.chen@curohelp.com',
                'phone' => '+971 4 123 4567',
                'gender' => 'male',
                'job_role_slug' => 'chauffeur',
                'city' => 'Dubai',
                'country' => 'UAE',
                'bio' => 'Professional chauffeur with advanced driving certifications and security training.',
                'years_of_experience' => 10,
                'hourly_rate' => 40.00,
                'skills' => ['Defensive Driving', 'Vehicle Maintenance', 'Route Planning', 'Discretion'],
                'languages' => ['English', 'Mandarin', 'Arabic'],
                'profile_image' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&h=400&fit=crop',
                'is_featured' => true,
            ],
            [
                'name' => 'Emily Watson',
                'email' => 'emily.watson@curohelp.com',
                'phone' => '+1 (212) 555-7890',
                'gender' => 'female',
                'job_role_slug' => 'personal-assistant',
                'city' => 'New York',
                'country' => 'United States',
                'bio' => 'Highly organized personal assistant with expertise in complex schedule management.',
                'years_of_experience' => 8,
                'hourly_rate' => 50.00,
                'skills' => ['Calendar Management', 'Travel Coordination', 'Communication', 'Project Management'],
                'languages' => ['English', 'Spanish'],
                'linkedin_url' => 'https://linkedin.com/in/emilywatson',
                'profile_image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=400&fit=crop',
                'is_featured' => true,
            ],
            [
                'name' => 'Robert Hamilton',
                'email' => 'robert.hamilton@curohelp.com',
                'phone' => '+41 22 123 4567',
                'gender' => 'male',
                'job_role_slug' => 'estate-manager',
                'city' => 'Geneva',
                'country' => 'Switzerland',
                'bio' => 'Experienced estate manager overseeing multi-property portfolios for UHNW clients.',
                'years_of_experience' => 20,
                'hourly_rate' => 75.00,
                'skills' => ['Property Management', 'Staff Supervision', 'Budgeting', 'Vendor Relations'],
                'languages' => ['English', 'French', 'German'],
                'profile_image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=400&h=400&fit=crop',
                'is_featured' => true,
            ],
            [
                'name' => 'Sophie Laurent',
                'email' => 'sophie.laurent@curohelp.com',
                'phone' => '+33 4 93 12 34 56',
                'gender' => 'female',
                'job_role_slug' => 'housekeeper',
                'city' => 'Cannes',
                'country' => 'France',
                'bio' => 'Meticulous housekeeper with expertise in luxury property maintenance.',
                'years_of_experience' => 14,
                'hourly_rate' => 35.00,
                'skills' => ['Deep Cleaning', 'Laundry Care', 'Organization', 'Inventory Management'],
                'languages' => ['French', 'English'],
                'profile_image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=400&h=400&fit=crop',
                'is_featured' => true,
            ],
            [
                'name' => 'Marcus Steel',
                'email' => 'marcus.steel@curohelp.com',
                'phone' => '+44 20 7946 0958',
                'gender' => 'male',
                'job_role_slug' => 'bodyguard',
                'city' => 'London',
                'country' => 'United Kingdom',
                'bio' => 'Ex-military close protection officer with advanced security certifications.',
                'years_of_experience' => 16,
                'hourly_rate' => 85.00,
                'skills' => ['Close Protection', 'Threat Assessment', 'Firearms Training', 'First Response'],
                'languages' => ['English', 'Russian'],
                'profile_image' => 'https://images.unsplash.com/photo-1566492031773-4f4e44671857?w=400&h=400&fit=crop',
                'is_featured' => true,
            ],
        ];

        foreach ($employees as $employeeData) {
            $jobRole = $jobRoles->where('slug', $employeeData['job_role_slug'])->first();
            
            if ($jobRole) {
                unset($employeeData['job_role_slug']);
                
                Employee::create(array_merge($employeeData, [
                    'job_role_id' => $jobRole->id,
                    'is_active' => true,
                    'is_available' => true,
                ]));
            }
        }
    }
}
