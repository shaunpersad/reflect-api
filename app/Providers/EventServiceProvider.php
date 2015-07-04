<?php

namespace App\Providers;

use App\Models\AbstractAppModel;
use App\Models\AccessToken;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Discussion;
use App\Models\EmailDomain;
use App\Models\Observers\CacheObserver;
use App\Models\Observers\OrganizationObserver;
use App\Models\Organization;
use App\Models\OrganizationSettings;
use App\Models\Permission;
use App\Models\Reflection;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Symfony\Component\Security\Core\Role\Role;

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
    ];


}
