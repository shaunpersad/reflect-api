<?php
namespace App\Listeners\OrganizationRegistrationCompleted;

use App\Events\OrganizationRegistrationCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateOrganizationGoogleCalendar implements ShouldQueue {

	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  OrganizationRegistrationCompleted  $event
	 * @return void
	 */
	public function handle(OrganizationRegistrationCompleted $event)
	{
		//
	}

}
