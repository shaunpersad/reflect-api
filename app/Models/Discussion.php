<?php


namespace App\Models;


use App\Models\Traits\MultiTenantTrait;

class Discussion extends AbstractAppModel {

    use MultiTenantTrait;

    public function user() {

        return $this->belongsTo('App\Models\User');
    }

    public function comments() {

        return $this->hasMany('App\Models\Comment');
    }
} 