<?php

namespace App\Console\Commands;

use App\PatchDay;
use App\Services\GenerateProtocolsService;
use Illuminate\Console\Command;

class GenerateProtocols extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'protocols:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate protocols for all patch days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // for some reason tests fail with less entries than a chunked 'page'
        // so we're not paginating if there's less entries
        $limit = 50;
        $actual = PatchDay::where('active', true)->count();

        $this->info('Getting started...');

        if ($actual > $limit) {
            PatchDay::with('project')
                ->where('active', true)
                ->orderBy('id')
                ->chunk(50, function ($patchDays) {
                    foreach ($patchDays as $patchDay) {
                        $this->info('Protocols for ' . $patchDay->project->name);
                        (new GenerateProtocolsService($patchDay))->go();
                    }
                });
        } else {
            $patchDays = PatchDay::with('project')
                ->where('active', true)
                ->orderBy('id');

            foreach ($patchDays as $patchDay) {
                $this->info('Protocols for ' . $patchDay->project->name);
                (new GenerateProtocolsService($patchDay))->go();
            }
        }

        $this->info('All done!');
    }
}
