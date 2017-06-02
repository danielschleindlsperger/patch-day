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

            // create random amount of patch-days for each project
            $patch_days = factory(\App\PatchDay::class, rand(0, 10))
                ->create([
                    'date' => $faker->dateTimeThisYear()->format('Y-m-d'),
                ]);
            $patch_days->each(function ($patch_day, $index) use ($patch_days) {
                $patch_day->date = Carbon::parse($patch_days[0]->date)
                    ->addWeeks($index)->toDateString();
                $patch_day->save();
            });

            // create protocols for patch-days
            foreach ($patch_days as $patch_day) {
                factory(\App\Protocol::class)->create([
                    'project_id' => $project->id,
                    'patch_day_id' => $patch_day->id,
                ])->each(function ($protocol, $key) use ($faker, $patch_day) {
                    // protocol assumed done when in past
                    $done = Carbon::parse($patch_day->date)->lessThan(Carbon::now());
                    $protocol->done = $done;
                    $protocol->comment = $done ? $faker->sentence(10) : null;
                    $protocol->save();
                });
            }

        }
    }
}
