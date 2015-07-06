<?php


namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Api\AbstractApiController;
use App\Services\Api\V1\Api;
use Illuminate\Contracts\Auth\PasswordBroker;

class AuthController extends AbstractApiController {

    /**
     * @var Api
     */
    protected $api;

    public function postLogin() {

        return $this->api->auth()->login($this->request->all());
    }

    public function postLogout() {

        return $this->api->auth()->logout($this->request->all());
    }

    public function postRegister() {

        return $this->api->auth()->register($this->request->all());
    }

    public function postRequestPasswordReset() {

        return $this->api->auth()->requestPasswordReset($this->request->all());
    }

    public function postResetPassword() {

        return $this->api->auth()->resetPassword($this->request->all());
    }

    protected function setupMiddleware() {

        $this->middleware('filter:mustBeLoggedIn', ['only' => ['logout']]);
    }
}