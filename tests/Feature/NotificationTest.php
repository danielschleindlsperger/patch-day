<?php

namespace Tests\Feature;

use App\Company;
use App\Mail\UpcomingPatchDay;
use App\PatchDay;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    /** @test */
    function it_notifies_about_upcoming_patch_days()
    {

        $user = factory(User::class)->create([
            'role' => 'client',
        ]);

        $patch_day = factory(PatchDay::class)->create([
            'date' => Carbon::now()->addDays(2)->toDateString(),
        ]);

        Mail::fake();

        Artisan::call('patch_day:notify-upcoming');

        Mail::assertSent(UpcomingPatchDay::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
