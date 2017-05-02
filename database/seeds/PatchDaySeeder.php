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
            factory(\App\PatchDay::class, rand(1, 5))->create()->each(function ($patchDay) use ($project, $faker) {
                $patchDay->cost = rand(10000, 50000);
                $patchDay->active = (bool)rand(0, 1);
                $patchDay->start_date = $faker->dateTimeThisYear;
                $patchDay->project_id = $project->id;
                $patchDay->save();
            });
        }
    }
}
