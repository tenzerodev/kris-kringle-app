<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a User
        $user = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // password is "password"
        ]);

        // Create a Group
        $group = \App\Models\Group::create([
            'admin_id' => $user->id,
            'name' => 'Office Christmas Party',
            'max_participants' => 10,
            'invite_code' => \Illuminate\Support\Str::uuid(),
            'status' => 'DRAFTING'
        ]);

        // Add Themes
        $themes = ['Something Red', 'Something Long', 'Something Soft', 'Something Electronic'];
        foreach ($themes as $t) {
            \App\Models\Theme::create([
                'group_id' => $group->id,
                'description' => $t
            ]);
        }
    }
}
