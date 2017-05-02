<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
            $patchDay = factory(\App\PatchDay::class)->create([
                'cost' => rand(10000, 50000),
                'start_date' => $startDate,
                'interval' => rand(1, 6),
                'active' => (bool)rand(0, 1),
                'project_id' => $project->id
            ]);

            // create random amount of protocols (a.k.a. actual patch
            // days on specific dates) for patch-day
            factory(\App\Protocol::class, rand(1, 5))->create([
                'patch_day_id' => $patchDay->id,
            ])->each(function ($protocol, $key) use (
                $patchDay, $startDate,
                $faker
            ) {
                // patch-day assumed done when it is in the past
                $dueDate = Carbon::parse($patchDay->start_date)
                    ->addMonths($key);
                $done = Carbon::now()->gt($dueDate);

                $protocol->patch_day_id = $patchDay->id;
                $protocol->done = $done;
                $protocol->comment = $done ? $faker->sentence(10) : null;
                $protocol->due_date = $dueDate;
                $protocol->save();
            });
        }
    }
}
