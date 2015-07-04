<?php namespace App\Events;

use App\Events\Event;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class UserRegistrationCompleted extends Event {

	use SerializesModels;

    public $user;

    /**
     * @param User $user
     */
	public function __construct(User $user)
	{
		//
        $this->user = $user;
	}

}
