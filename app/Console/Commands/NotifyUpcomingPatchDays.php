<?php

namespace App\Console\Commands;

use App\Mail\UpcomingPatchDay;
use App\PatchDay;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyUpcomingPatchDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch_day:notify-upcoming';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out emails to notify about upcoming patch-days';

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
        $patch_day = PatchDay::where('date', '>', Carbon::now()->toDateString())
            ->where('date', '<', Carbon::now()->addDays(7)->toDateString())
            ->first();
        if ($patch_day) {
            $this->info($patch_day->name);

            User::where('role', '=', 'client')
                ->chunk(20, function($users) use ($patch_day) {
                    foreach($users as $user) {
                        Mail::to($user->email)
                            ->send(new UpcomingPatchDay($patch_day, $user));
                    }
            });
        }
    }
}
