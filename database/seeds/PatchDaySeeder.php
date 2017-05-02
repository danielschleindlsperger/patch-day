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
            $startDate = $faker->dateTimeThisYear;

            // create one patch day for each project
            factory(\App\PatchDay::class)->create([
                'cost' => rand(10000, 50000),
                'start_date' => $startDate,
                'interval' => rand(1, 6),
                'active' => (bool)rand(0, 1),
                'project_id' => $project->id
            ])->each(function ($patchDay) use ($startDate, $faker) {
                // create random amount of protocols (a.k.a. actual patch
                // days on specific dates)
                factory(\App\Protocol::class, rand(1, 5))->create([
                    'comment' => $faker->sentence(10),
                    'patch_day_id' => $patchDay->id,
                ])->each(function ($protocol, $key) use (
                    $patchDay,
                    $startDate, $faker
                ) {
                    $protocol->patch_day_id = $patchDay->id;
                    $protocol->due_date = \Carbon\Carbon::parse
                    ($patchDay->start_date)->addMonths($key);
                    $protocol->save();
                });
            });
        }
    }
}
