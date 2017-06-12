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
        // prevent user model events that are used for notifications etc.
        \App\User::flushEventListeners();

        $coma = \App\Company::firstOrCreate([
            'name' => 'coma AG',
        ]);

        $admin = DB::table('users')->where('email', 'admin@patch-day.dev')
            ->first();
        if (!$admin) {
            factory(\App\User::class)->create([
                'name' => 'Admin',
                'email' => 'admin@patch-day.dev',
                'password' => bcrypt('secret'),
                'company_id' => $coma->id,
                'role' => 'admin',
            ]);
        }

        $client = DB::table('users')->where('email', 'client@example.com')
            ->first();
        if (!$client) {
            factory(\App\User::class)->create([
                'name' => 'Fake Fakerson',
                'email' => 'client@example.com',
                'password' => bcrypt('secret'),
                'company_id' => \App\Company::first()->id,
                'role' => 'client',
            ]);
        }

        $companies = \App\Company::where(
            'id', '!=', $coma->id
        )->get();

        foreach($companies as $company) {
            factory(\App\User::class, rand(1, 5))->create([
                'role' => 'client',
                'company_id' => $company->id,
            ]);
        }
    }
}
