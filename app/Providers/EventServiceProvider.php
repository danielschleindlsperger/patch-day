<?php

namespace App\Providers;

use App\Observers\PatchDayObserver;
use App\Observers\ProtocolObserver;
use App\Observers\UserObserver;
use App\PatchDay;
use App\Protocol;
use App\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Protocol::observe(new ProtocolObserver());
        PatchDay::observe(new PatchDayObserver());
        User::observe(new UserObserver());
    }
}
