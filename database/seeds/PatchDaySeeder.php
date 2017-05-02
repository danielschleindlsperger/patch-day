<?php

use Illuminate\Database\Seeder;

class PatchDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $projects = \App\Project::all();

        foreach ($projects as $project) {
            factory(\App\PatchDay::class)->create([
                'cost' => rand(10000, 50000),
                'start_date' => $faker->dateTimeThisYear,
                'interval' => rand(1, 6),
                'active' => (bool)rand(0, 1),
                'project_id' => $project->id
            ]);
        }
    }
}
