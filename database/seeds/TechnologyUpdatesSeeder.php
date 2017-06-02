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
            $updatedTechs = $protocol->project->technologies->pluck('name');

            foreach ($updatedTechs as $tech) {
                $technology = factory(\App\Technology::class)->create([
                    'name' => $tech,
                ]);
                $protocol->project->technologies()->attach($technology->id, ['protocol_id' => $protocol->id]);
            }
        }
    }
}
