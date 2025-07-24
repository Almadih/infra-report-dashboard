<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'device_id' => '59df38eec3e2021210aea3b6d833a636',
            ],
            [
                'device_id' => '63df38eec3e2021210aea3b6d833a646',
            ],
        ];

        foreach ($users as $user) {
            User::factory()->create([
                'email' => $user['device_id'].'@user.com',
                'password' => Hash::make('password'),
                'is_anonymous' => true,
                'device_id' => $user['device_id'],
            ]);

        }
    }
}
