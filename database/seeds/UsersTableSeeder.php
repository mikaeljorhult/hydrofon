<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->states(['admin'])->create([
            'name'     => 'Default User',
            'email'    => 'default@hydrofon.se',
            'password' => bcrypt('default'),
        ]);

        if (app()->environment('local')) {
            factory(User::class, 10)->create();
        }
    }
}
