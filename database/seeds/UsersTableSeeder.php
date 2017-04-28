<?php

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
        $admin = DB::table('users')->where('email', 'admin@patch-day.dev')
            ->first();
        if (!$admin) {
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => 'admin@patch-day.dev',
                'password' => bcrypt('secret'),
                'company_id' => \App\Company::first()->id,
                'role' => 'admin',
            ]);
        }
    }
}
