<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class AccessToken extends Model {

    protected $hidden = ['id', 'user_id', 'user'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'access_tokens';

    public function user() {

        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public static function boot() {

        parent::boot();

        self::saved(function(AccessToken $access_token) {

            $cache = app('Illuminate\Contracts\Cache\Repository');

            $user = $access_token->user;

            $cache->put($access_token->token, $user->id, $access_token->expires_at);

        });

        self::deleted(function(AccessToken $access_token) {

            $cache = app('Illuminate\Contracts\Cache\Repository');

            $cache->forget($access_token->token);
        });

    }

    public static function make(User $user) {

        $access_token = new AccessToken();
        $access_token->token = str_random(255);
        $access_token->user_id = $user->id;

        $access_token->expires_at = new \DateTime('now + 30 days');

        $successful = false;
        while (!$successful) {

            try {

                $successful = $access_token->save();

            } catch (QueryException $e) {
                $successful = false;
            }
        }

        return $access_token;
    }
}