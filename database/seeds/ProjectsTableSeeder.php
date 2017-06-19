<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $companies = \App\Company::all();

        foreach ($companies as $company) {
            factory(\App\Project::class, rand(1, 5))->create([
                'company_id' => $company->id,
            ])->each(function ($project) use ($faker) {
                $project->name = $faker->words(2, true);
                $project->save();
            });
        }

        // attach default technologies to projects
        $technologies = \App\Technology::all();
        $projects = \App\Project::all();
        $tech_ids = [];

        foreach ($projects as $project) {
            $technologies->shuffle()->unique('name')->splice(0,
                rand(2, 4))->each(function ($tech) use (&$tech_ids) {
                array_push($tech_ids, $tech->id);
            });

            $project->technologies()->attach($tech_ids);
        }
    }
}
