<?php
namespace App\Listeners\OrganizationRegistrationCompleted;

use App\Events\OrganizationRegistrationCompleted;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailInvitees implements ShouldQueue {

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
	}

}
