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
        factory(\App\Project::class, 200)->create()->each(function ($project) use ($companies) {
            $project->company_id = $companies->random()->id;
            $project->save();
        });
    }
}
