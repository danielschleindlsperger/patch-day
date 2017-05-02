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
            factory(\App\Project::class, rand(1, 5))->create()->each(function ($project) use ($company, $faker) {
                $project->name = $faker->words(2, true);
                $project->company_id = $company->id;
                $project->save();
            });
        }
    }
}
