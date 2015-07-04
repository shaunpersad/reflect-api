<?php


namespace App\Models;


class Category extends AbstractAppModel {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';

    public function organizations() {

        return $this->belongsToMany(
            'App\Models\Organization',
            'organizations_categories',
            'category_id',
            'organization_id'
        );
    }
} 