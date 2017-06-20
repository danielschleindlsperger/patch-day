<?php

use Illuminate\Database\Seeder;

class TechnologyUpdatesSeeder extends Seeder
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

        $protocols = \App\Protocol::with('project', 'project.technologies')
            ->get();

        foreach ($protocols as $protocol) {
            $techs = $protocol->project->technologies->unique('name')->pluck('name');
            $maxUpdates = $techs->count() - 1;

            $updatedTechs = $techs->shuffle()->splice(0, rand(1, $maxUpdates));

            foreach ($updatedTechs as $tech) {
                $technology = factory(\App\Technology::class)->create([
                    'name' => $tech,
                ]);
                $protocol->project->technologies()->attach($technology->id, ['protocol_id' => $protocol->id]);
            }
        }
    }
}
