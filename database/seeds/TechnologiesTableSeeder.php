<?php

use Illuminate\Database\Seeder;

class TechnologiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $techs = [
            'Vue.js',
            'Laravel',
            'Node.js',
            'Wordpress',
            'jQuery',
            'php',
            'Bootstrap',
        ];

        foreach ($techs as $tech) {
            factory(\App\Technology::class, rand(1, 10))->create([
                'name' => $tech,
            ]);
        }
    }
}
