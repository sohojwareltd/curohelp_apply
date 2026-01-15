<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(JobRoleSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(LocationSeeder::class);

        $roles = collect([
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Candidate', 'slug' => 'candidate', 'is_default' => true],
            ['name' => 'Client', 'slug' => 'client'],
            ['name' => 'Remote Worker', 'slug' => 'remote_worker'],
            ['name' => 'Estate Manager', 'slug' => 'estate_manager'],
            ['name' => 'Butler', 'slug' => 'butler'],
            ['name' => 'Housekeeper', 'slug' => 'housekeeper'],
            ['name' => 'Private Chef', 'slug' => 'private_chef'],
            ['name' => 'Chauffeur', 'slug' => 'chauffeur'],
            ['name' => 'Nanny', 'slug' => 'nanny'],
        ]);

        $roles->each(fn ($role) => Role::firstOrCreate(['slug' => $role['slug']], $role));

        // Legacy inline location seeding removed in favor of LocationSeeder reading from resource files.

        $admin = User::firstOrCreate(
            ['email' => 'admin@curocore.test'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );

        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole && ! $admin->roles()->where('roles.id', $adminRole->id)->exists()) {
            $admin->roles()->attach($adminRole->id);
        }
    }
}
