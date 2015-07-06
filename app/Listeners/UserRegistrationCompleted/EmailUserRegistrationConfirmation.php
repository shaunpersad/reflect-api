<?php
namespace App\Listeners\UserRegistrationCompleted;

use App\Events\UserRegistrationCompleted;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailUserRegistrationConfirmation implements ShouldQueue {

    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        //
        $this->mailer = $mailer;
    }

	/**
	 * Handle the event.
	 *
	 * @param  UserRegistrationCompleted  $event
	 * @return void
	 */
	public function handle(UserRegistrationCompleted $event)
	{
        $user = $event->user;
        //
        $this->mailer->send(
            'emails.user-registration-completed',
            ['user' => $user],
            function($message) use ($user) {

                $message->subject('Thanks for joining reflect!');
                $message->to($user->email);

            });
	}

}
