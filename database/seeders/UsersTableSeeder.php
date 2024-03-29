<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Default User',
            'email' => 'default@hydrofon.se',
            'password' => bcrypt('default'),
        ]);

        if (app()->environment('local')) {
            User::factory()->times(10)->create();
        }
    }
}
