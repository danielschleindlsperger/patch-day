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

        // create patch-day each month
        $patch_days = factory(\App\PatchDay::class, 15)
            ->create([
                'date' => Carbon::now()->subMonths(6)->format('Y-m-d'),
            ]);
        $patch_days->each(function ($patch_day, $index) use ($patch_days) {
            $patch_day->date = Carbon::parse($patch_days[0]->date)
                ->addMonths($index)->toDateString();
            $patch_day->save();
        });

        foreach ($projects as $project) {

            $intervalStart = Carbon::now()->subMonths(6);
            $intervalEnd = Carbon::now()->addMonths(2);

            $timestamp = mt_rand($intervalStart->timestamp,
                $intervalEnd->timestamp);

            $firstDate = Carbon::createFromTimestamp($timestamp)->toDateString();

            $registeredPatchDays = \App\PatchDay::where('date', '>=',
                $firstDate)
                ->where('date', '<', $intervalEnd->toDateString())->get();

            foreach ($registeredPatchDays as $patch_day) {
                $protocol = factory(\App\Protocol::class)->create([
                    'project_id' => $project->id,
                    'patch_day_id' => $patch_day->id,
                ]);
                // protocol assumed done when in past
                $done = Carbon::parse($patch_day->date)->lessThan(Carbon::now());
                $protocol->done = $done;
                $protocol->comment = $done ? $faker->sentence(10) : null;
                $protocol->save();
            }
        }
    }
}
