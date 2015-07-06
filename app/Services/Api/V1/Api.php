<?php


namespace App\Services\Api\V1;


use App\Contracts\ApiContract;
use App\Exceptions\UnauthorizedException;
use App\Models\Organization;
use App\Models\User;
use App\Services\Api\V1\Resources\Auth\AuthResource;
use App\Services\Api\V1\Resources\Organizations\OrganizationsResource;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Cache\Repository as Cache;

class Api implements ApiContract {

    /**
     * @var Guard
     */
    public  $guard;

    /**
     * @var ValidationFactory
     */
    public $validation_factory;

    /**
     * @var EventDispatcher
     */
    public $dispatcher;

    /**
     * @var User|null
     */
    public  $resource_owner;

    /**
     * @var Cache
     */
    public $cache;


    public function __construct(Guard $guard, ValidationFactory $validation_factory, EventDispatcher $event, Cache $cache) {

        $this->guard = $guard;
        $this->validation_factory = $validation_factory;
        $this->event = $event;
        $this->cache = $cache;
    }

    /**
     * @return User
     * @throws UnauthorizedException
     */
    public function user() {

        $user = $this->guard->user();
        if (!$user) {
            throw new UnauthorizedException();
        }
        return $user;
    }


    public function organizations(Organization $organization = null) {

        return new OrganizationsResource($this, $organization);
    }

    public function auth() {

        return new AuthResource($this);
    }
}