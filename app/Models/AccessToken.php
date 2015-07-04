<?php


namespace App\Models;


class AccessToken extends AbstractAppModel {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'access_tokens';

    public function user() {

        return $this->belongsTo('App\Models\User', 'user_id');
    }
}