<?php


namespace App\Models;


use App\Models\Traits\MultiTenantTrait;

class Reflection extends AbstractAppModel {

    use MultiTenantTrait;

    const DAY_OF_WEEK_SUNDAY = 0;
    const DAY_OF_WEEK_MONDAY = 1;
    const DAY_OF_WEEK_TUESDAY = 2;
    const DAY_OF_WEEK_WEDNESDAY = 3;
    const DAY_OF_WEEK_THURSDAY = 4;
    const DAY_OF_WEEK_FRIDAY = 5;
    const DAY_OF_WEEK_SATURDAY = 6;

    public function discussions() {

        return $this->belongsToMany(
            'App\Models\Discussion',
            'reflections_discussions',
            'reflection_id',
            'discussion_id'
        );
    }
} 