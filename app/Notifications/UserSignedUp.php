<?php

namespace App\Notifications;

use App\User;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class UserSignedUp extends Notification
{
    public $user;

    /**
     * @param User $user The created user instance.
     *
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $user->load('company');
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $user = $this->user;

        return (new SlackMessage)
            ->content('New user signed up!')
            ->attachment(function ($attachment) use ($user) {

                $url = url('/') . '/#/users/' . $user->id;

                $attachment->title('User', $url)
                    ->fields([
                        'Name'    => $user->name,
                        'E-Mail'  => $user->email,
                        'Role'    => $user->role,
                        'Company' => $user->company->name,
                    ]);
            })
            ->from('BOT', ':robot_face:');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'content' => 'New user signed up!',
            'url'     => url('/') . '/#/users/' . $this->user->id,
            'name'    => $this->user->name,
            'email'   => $this->user->email,
            'role'    => $this->user->role,
            'company' => $this->user->company->name,
        ];
    }
}
