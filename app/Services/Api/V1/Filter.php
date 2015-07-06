<?php


namespace App\Services\Api\V1;


use App\Contracts\ApiContract;
use App\Exceptions\ForbiddenException;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;

class Filter {

    /**
     * @var Api
     */
    protected $api;

    public function __construct(ApiContract $api) {
        $this->api = $api;
    }

    /**
     * @param string $message
     * @return User
     * @throws \App\Exceptions\ForbiddenException
     * @throws \App\Exceptions\MultiTenantException
     */
    public function adminsOnly($message = 'Only admins may do this.') {

        $user = $this->mustBeLoggedIn();

        if ($user->hasRole(Role::ROLE_SUPER_ADMIN)) {

            return $user;
        }

        $organization = Organization::current();

        if ($user->hasRole($organization->roleAdminName())) {

            return $user;
        }

        throw new ForbiddenException($message);
    }

    /**
     * @param string $message
     */
    public function internalServiceOnly($message = '') {

        // TODO
    }

    /**
     * @param string $message
     * @return User
     * @throws \App\Exceptions\ForbiddenException
     */
    public function mustBeLoggedIn($message = 'A logged in user is required to do this.') {

        return $this->api->user();
    }

    /**
     * @param string $message
     * @return User
     * @throws \App\Exceptions\ForbiddenException
     */
    public function mustHaveResourceOwner($message = 'A resource owner was not found.') {

        if (!empty($this->api->resource_owner)) {
            return $this->api->resource_owner;
        }
        throw new ForbiddenException($message);
    }

    /**
     * @param string $message
     * @return User
     * @throws \App\Exceptions\ForbiddenException
     */
    public function notOwner($message = 'The logged in user must not be the resource owner.') {

        $user = $this->mustBeLoggedIn();
        $resource_owner = $this->mustHaveResourceOwner();

        if ($user->id == $resource_owner->id) {

            throw new ForbiddenException($message);
        }
        return $resource_owner;
    }

    /**
     * @param string $message
     * @return User
     * @throws \App\Exceptions\ForbiddenException
     */
    public function ownerOrAdmins($message = 'The logged in user must be either the resource owner or an admin.') {

        $user = $this->mustBeLoggedIn();
        $resource_owner = $this->mustHaveResourceOwner();

        if ($user->id != $resource_owner->id) {

            $this->adminsOnly($message);
        }
        return $resource_owner;
    }

    public function publicAccess() {

        // TODO: check client_id
    }

    public function superAdminsOnly($message = 'Only super admins may do this.') {

        $user = $this->mustBeLoggedIn();
        if (
            !empty($user) &&
            $user->hasRole(Role::ROLE_SUPER_ADMIN)
        ) {

            return $user;
        }
        throw new ForbiddenException($message);
    }


} 