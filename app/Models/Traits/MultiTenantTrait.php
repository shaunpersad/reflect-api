<?php


namespace App\Models\Traits;


use App\Models\Organization;
use App\Models\Scopes\MultiTenantScope;
use Log;

trait MultiTenantTrait {

    public static function bootMultiTenantTrait() {

        static::addGlobalScope(new MultiTenantScope());

        static::saving(function($model) {

            $organization = Organization::current();

            $model->organization_id = $organization->id;
        });

    }
} 