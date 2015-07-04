<?php


namespace App\Http\Controllers\Api\Organizations;


use App\Http\Controllers\Api\AbstractApiController;
use App\Models\Organization;
use App\Services\Api\V1\Api;

class OrganizationsController extends AbstractApiController {

    /**
     * @var Api
     */
    protected $api;

    protected function setupMiddleware() {

        $this->middleware('filter:superAdminsOnly', ['only' => ['getAll']]);
        $this->middleware('filter:mustBeLoggedIn', ['only' => ['postCompleteRegistration']]);
    }

    public function getOne($organization_id) {

        $organization = Organization::findOrFailCached($organization_id);

        return $this->api->organizations($organization)->defaultAction($this->request->all());
    }

    public function getAll() {

        return $this->api->organizations()->defaultAction($this->request->all());
    }

    public function postBeginRegistration() {

        return $this->api->organizations()->beginRegistration($this->request->all());
    }

    public function postCompleteRegistration() {

        return $this->api->organizations()->completeRegistration($this->request->all());
    }
}