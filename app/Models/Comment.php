<?php


namespace App\Models;


use App\Models\Traits\MultiTenantTrait;

class Comment extends AbstractAppModel {

    use MultiTenantTrait;

    public function user() {

        return $this->belongsTo('App\Model\User');
    }

    public function discussion() {

        return $this->belongsTo('App\Model\Discussion');
    }
} 