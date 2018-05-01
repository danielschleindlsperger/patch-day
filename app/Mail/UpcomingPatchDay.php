<?php

namespace App\Mail;

use App\PatchDay;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpcomingPatchDay extends Mailable
{
    use Queueable, SerializesModels;

    public $patch_day;
    public $user;

    /**
     * Create a new message instance.
     * @param PatchDay $patch_day
     * @param User     $user
     */
    public function __construct(PatchDay $patch_day, User $user)
    {
        $this->patch_day = $patch_day;
        $this->user      = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.patch_day.upcoming');
    }
}
