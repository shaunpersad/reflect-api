<?php namespace App\Events;

use App\Events\Event;

use App\Models\Organization;
use Illuminate\Queue\SerializesModels;

class OrganizationRegistrationCompleted extends Event {

	use SerializesModels;

    public $organization;

    /**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(Organization $organization)
	{
		//
        $this->organization = $organization;
	}

}
