<?php


namespace App\Services\Api\V1\Resources\Auth;


use App\Events\UserRegistrationCompleted;
use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\UnauthorizedException;
use App\Models\User;
use App\Services\Api\V1\Resources\AbstractResource;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Database\QueryException;

class AuthResource extends AbstractResource {

    public static function endpointFilters($resource) {

        return array(
            'login' => 'publicAccess',
            'logout' => 'mustBeLoggedIn',
            'register' => 'publicAccess',
            'requestPasswordReset' => 'publicAccess',
            'resetPassword' => 'publicAccess'
        );
    }

    /**
     * @param array $params
     * @return User|null
     * @throws \App\Exceptions\UnauthorizedException
     */
    public function login($params = array()) {

        $defaults = array(
            'email' => $email = null,
            'password' => $password = null,
            'remember' => $remember = false
        );

        $rules = array(
            'email' => array('required', 'email', 'exists:users,email,login_type,'.User::LOGIN_TYPE_PASSWORD.',deleted_at,NULL'),
            'password' => array('required')
        );

        $params = $this->validateParams($defaults, $params, $rules);

        extract($params);

        if ($this->api->guard->attempt(array(
            'email' => $email,
            'password' => $password,
            'login_type' => User::LOGIN_TYPE_PASSWORD,
            'deleted_at' => null
        ), $remember)) {

            $this->api->user =  $this->api->guard->user();
        }

        if (empty($this->api->user)) {
            throw new UnauthorizedException('Invalid credentials. Please try again.');
        }

        return $this->api->user;

    }

    /**
     * @param array $params
     * @return User|null
     */
    public function logout($params = array()) {

        $user = $this->api->user;

        $this->api->guard->logout();

        return $user;
    }

    /**
     * @param array $params
     * @return User
     * @throws \App\Exceptions\BadRequestException
     */
    public function register($params = array()) {

        $defaults = array(
            'email' => $email = null,
            'password' => $password = null,
            'name' => $name = null
        );

        $rules = array(
            'email' => array('required', 'email', 'max:255', 'unique:users,email,NULL,id,login_type,'.User::LOGIN_TYPE_PASSWORD),
            'password' => array('required', 'confirmed', 'min:6'),
            'name' => array('required', 'max:100')
        );

        $params = $this->validateParams($defaults, $params, $rules);

        extract($params);

        $user = new User();
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->login_type = User::LOGIN_TYPE_PASSWORD;
        $user->name = $name;

        try {
            $user->save();

            $this->api->guard->login($user);

            $this->api->user = $this->api->guard->user();

            $this->api->event->fire(new UserRegistrationCompleted($user));

            return $this->api->user;

        } catch(QueryException $e) {

            throw new BadRequestException('This user already exists.');
        }

    }

    /**
     * @param array $params
     * @return null
     * @throws \App\Exceptions\BadRequestException
     */
    public function requestPasswordReset($params = array()) {

        $defaults = array(
            'email' => $email = null
        );

        $rules = array(
            'email' => array('required', 'email', 'exists:users,email,login_type,'.User::LOGIN_TYPE_PASSWORD.',deleted_at,NULL'),
        );

        $params = $this->validateParams($defaults, $params, $rules);

        extract($params);

        $password_broker = app('Illuminate\Contracts\Auth\PasswordBroker');

        $response = $password_broker->sendResetLink(['email' => $email], function($m) {
            $m->subject('Your password reset link.');
        });

        if ($response == PasswordBroker::RESET_LINK_SENT) {
            return ['email' => $email];
        }

        throw new BadRequestException('Please request a new password reset.');
    }

    /**
     * @param array $params
     * @return User|null
     * @throws \App\Exceptions\BadRequestException
     */
    public function resetPassword($params = array()) {

        $defaults = array(
            'token' => $token = null,
            'email' => $email = null,
            'password' => $password = null
        );

        $rules = array(
            'token' => array('required'),
            'email' => array('required', 'email', 'exists:users,email,login_type,'.User::LOGIN_TYPE_PASSWORD.',deleted_at,NULL'),
            'password' => array('required', 'confirmed', 'min:6')
        );

        $params = $this->validateParams($defaults, $params, $rules);

        extract($params);

        $password_broker = app('Illuminate\Contracts\Auth\PasswordBroker');

        $response = $password_broker->reset($params, function(User $user, $password) {
            $user->password = bcrypt($password);

            $user->save();

            $this->api->guard->login($user);
        });

        if ($response == PasswordBroker::PASSWORD_RESET) {

            $this->api->user = $this->api->guard->user();
            return $this->api->user;
        }

        throw new BadRequestException('Please request a new password reset.');
    }

} 