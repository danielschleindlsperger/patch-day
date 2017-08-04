<?php

use Illuminate\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Admin',
            'email' => 'admin@patch-day.dev',
            'password' => bcrypt('securepassword'),
            'role' => 'admin',
        ]);
    }
}
