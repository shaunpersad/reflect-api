<?php
namespace App\Listeners\OrganizationRegistrationBegan;

use App\Events\OrganizationRegistrationBegan;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailOrganizationRegistrant implements ShouldQueue {

    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        //
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  OrganizationRegistrationBegan $event
     * @return void
     */
	public function handle(OrganizationRegistrationBegan $event) {
		//
        $organization = $event->organization;
        $email = $organization->registration_email;
        $url = route('complete-organization-registration', ['verification_code' => $organization->verification_code]);

        $this->mailer->send(
            'emails.organization-registration-began',
            ['organization' => $organization, 'url' => $url],
            function($message) use ($email) {

                $message->subject('Continue with your reflect registration');
                $message->to($email);

            });
	}

}
