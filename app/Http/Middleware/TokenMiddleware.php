<?php


namespace App\Http\Middleware;


use App\Models\AccessToken;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class TokenMiddleware {

    protected $guard;

    public function __construct(Guard $guard) {

        $this->guard = $guard;
    }

    /**
     * Handle an incoming request.
     * Get the user associated with the access_token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($token = $request->input('access_token', false)) {

            $cache = app('Illuminate\Contracts\Cache\Repository');

            $user = false;

            if ($cache->has($token)) {

                $user_id = $cache->get($token, false);

                $user = User::findOrFailCached($user_id);

            } elseif ($access_token = AccessToken::where('token', $token)->where('expires_at', '>', new \DateTime())->first()) {

                /**
                 * Logging this because this shouldn't happen.
                 */
                app('log')->info('Looking up token in database.');

                $user = $access_token->user;

                $cache->put($access_token->token, $user->id, $access_token->expires_at);
            }

            if ($user) {

                $this->guard->login($user);
            }

        }
        return $next($request);
    }
}