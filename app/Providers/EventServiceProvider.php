<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\OrganizationRegistrationBegan' => [
            'App\Listeners\OrganizationRegistrationBegan\EmailOrganizationRegistrant',
        ],
        'App\Events\OrganizationRegistrationCompleted' => [
            'App\Listeners\OrganizationRegistrationCompleted\EmailOrganizationRegistrantConfirmation',
            'App\Listeners\OrganizationRegistrationCompleted\EmailInvitees',
            'App\Listeners\OrganizationRegistrationCompleted\CreateOrganizationGoogleCalendar',
        ],
        'App\Events\UserRegistrationCompleted' => [
            'App\Listeners\UserRegistrationCompleted\EmailUserRegistrationConfirmation'
        ],
        'App\Events\PasswordResetRequested' => [
            'App\Listeners\PasswordResetRequested\EmailPasswordResetLink'
        ],
    ];


}
