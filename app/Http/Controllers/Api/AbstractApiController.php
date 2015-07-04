<?php


namespace App\Http\Controllers\Api;


use App\Contracts\ApiContract;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

abstract class AbstractApiController extends Controller {

    protected $api;
    protected $request;

    public function __construct(ApiContract $api, Request $request) {

        $this->api = $api;
        $this->request = $request;

        if ($organization_id = $request->get('organization_id', false)) {

            $organization = Organization::findOrFailCached($organization_id);

            Organization::setCurrent($organization);
        }

        $this->setupMiddleware();
    }

    abstract protected function setupMiddleware();

}