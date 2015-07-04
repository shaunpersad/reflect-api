<?php namespace App\Events;

use App\Events\Event;

use App\Models\Organization;
use Illuminate\Queue\SerializesModels;

class OrganizationRegistrationBegan extends Event {

	use SerializesModels;

    public $organization;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Organization $organization
     * @return \App\Events\OrganizationRegistrationBegan
     */
	public function __construct(Organization $organization) {

        $this->organization = $organization;
	}

}
