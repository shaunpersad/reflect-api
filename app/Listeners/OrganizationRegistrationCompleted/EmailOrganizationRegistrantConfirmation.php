<?php
namespace App\Listeners\OrganizationRegistrationCompleted;

use App\Events\OrganizationRegistrationCompleted;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailOrganizationRegistrantConfirmation implements ShouldQueue {

    protected $mailer;

	public function __construct(Mailer $mailer)
	{
		//
        $this->mailer = $mailer;
	}

    /**
     * Handle the event.
     *
     * @param  OrganizationRegistrationCompleted $event
     * @return void
     */
	public function handle(OrganizationRegistrationCompleted $event)
	{
		//
        $organization = $event->organization;
        $email = $organization->registration_email;

        $this->mailer->send(
            'emails.organization-registration-completed',
            ['organization' => $organization],
            function($message) use ($email) {

                $message->subject('All set!');
                $message->to($email);

            });
	}

}
