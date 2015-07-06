<?php


namespace App\Services\Api\V1\Resources\Auth;


use App\Events\PasswordResetRequested;
use App\Events\UserRegistrationCompleted;
use App\Exceptions\BadRequestException;
use App\Models\AccessToken;
use App\Models\User;
use App\Services\Api\V1\Resources\AbstractResource;
use Illuminate\Database\QueryException;

class AuthResource extends AbstractResource {

    /**
     * @param array $params
     * @return mixed
     * @throws BadRequestException
     */
    public function login($params = array()) {

        $defaults = array(
            'email' => $email = null,
            'password' => $password = null
        );

        $rules = array(
            'email' => array('required', 'email', 'exists:users,email,login_type,'.User::LOGIN_TYPE_PASSWORD.',deleted_at,NULL'),
            'password' => array('required')
        );

        $params = $this->validateParams($defaults, $params, $rules);

        extract($params);

        if (!$this->api->guard->attempt(array(
            'email' => $email,
            'password' => $password,
            'login_type' => User::LOGIN_TYPE_PASSWORD,
            'deleted_at' => null
        ))) {
            throw new BadRequestException("Your credentials are incorrect. Please try again.");
        }

        $user = $this->api->user();

        $access_token = AccessToken::make($user);

        return AccessToken::where('token', $access_token->token)->firstOrFail();

    }

    /**
     * @param array $params
     * @return User|null
     */
    public function logout($params = array()) {

        $user = $this->api->user();

        $this->api->guard->logout();

        foreach($user->accessTokens()->get() as $access_token) {

            $access_token->delete();
        }

        return $user;
    }

    /**
     * @param array $params
     * @return AccessToken
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

            $user = $this->api->user();

            $this->api->event->fire(new UserRegistrationCompleted($user));

            return $this->login($params);

        } catch(QueryException $e) {

            throw new BadRequestException('This user already exists.');
        }

    }

    /**
     * @param array $params
     * @return mixed
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

        $this->api->event->fire(new PasswordResetRequested($email));

        return ['email' => $email];

    }

    /**
     * @param array $params
     * @return User
     */
    public function resetPassword($params = array()) {

        $defaults = array(
            'token' => $token = null,
            'email' => $email = null,
            'password' => $password = null
        );

        $rules = array(
            'token' => array('required', 'exists:password_resets,token,email,'.@$params['email']),
            'email' => array('required', 'email', 'exists:users,email,login_type,'.User::LOGIN_TYPE_PASSWORD.',deleted_at,NULL'),
            'password' => array('required', 'confirmed', 'min:6')
        );

        $params = $this->validateParams($defaults, $params, $rules);

        extract($params);

        $user = User::where('email', $email)->where('login_type', User::LOGIN_TYPE_PASSWORD)->firstOrFail();

        $user->password = bcrypt($password);

        $user->save();

        $this->api->guard->login($user);

        app('db')->table('password_resets')->where('token', $token)->where('email', $email)->delete();

        $this->logout();

        return $user;

    }

} 