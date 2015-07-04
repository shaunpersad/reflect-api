<?php


namespace App\Models;


class EmailDomain extends AbstractAppModel {

    protected $table = 'email_domains';

    protected $fillable = array('domain');

    public function organization() {

        return $this->belongsTo('App\Models\Organization');
    }
} 