<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Role;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientWorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
        $clientRole = Role::firstOrCreate(
            ['slug' => 'client'],
            [
                'name' => 'Client',
                'description' => 'Client user role',
                'is_default' => false,
            ]
        );

        $workerRole = Role::firstOrCreate(
            ['slug' => 'worker'],
            [
                'name' => 'Worker',
                'description' => 'Worker user role',
                'is_default' => false,
            ]
        );

        // Create example client user
        $clientUser = User::firstOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'John Client',
                'password' => Hash::make('password'),
            ]
        );

        // Attach client role
        if (!$clientUser->roles()->where('slug', 'client')->exists()) {
            $clientUser->roles()->attach($clientRole->id);
        }

        // Create client profile
        Client::firstOrCreate(
            ['user_id' => $clientUser->id],
            [
                'name' => 'John Client',
                'phone' => '+1234567890',
                'email' => 'client@example.com',
                'address' => '123 Client Street, Business District, New York, NY 10001',
            ]
        );

        // Create example worker user
        $workerUser = User::firstOrCreate(
            ['email' => 'worker@example.com'],
            [
                'name' => 'Jane Worker',
                'password' => Hash::make('password'),
            ]
        );

        // Attach worker role
        if (!$workerUser->roles()->where('slug', 'worker')->exists()) {
            $workerUser->roles()->attach($workerRole->id);
        }

        // Create worker profile
        Worker::firstOrCreate(
            ['user_id' => $workerUser->id],
            [
                'name' => 'Jane Worker',
                'phone' => '+1234567891',
                'email' => 'worker@example.com',
                'address' => '456 Worker Avenue, Service District, Los Angeles, CA 90001',
            ]
        );

        $this->command->info('✓ Client and Worker example users created successfully!');
        $this->command->info('  Client: client@example.com / password');
        $this->command->info('  Worker: worker@example.com / password');
    }
}
