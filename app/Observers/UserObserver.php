<?php

namespace App\Observers;

use App\Notifications\UserSignedUp;
use App\User;
use Illuminate\Support\Facades\Notification;

class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  User $user
     * @return void
     */
    public function created(User $user)
    {
        $admins = User::where('role', 'admin')->get();

        Notification::send($admins, new UserSignedUp($user));
    }
}