<?php


namespace App\Models\Scopes;


use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ScopeInterface;

class MultiTenantScope implements ScopeInterface {

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model) {

        $organization = Organization::current();

        $builder->where('organization_id', $organization->id);
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function remove(Builder $builder, Model $model) {

        $organization = Organization::current();

        $query = $builder->getQuery();

        foreach ((array) $query->wheres as $key => $where) {

            if ($where['type'] == $organization->id && $where['column'] == 'organization_id') {

                unset($query->wheres[$key]);

                $query->wheres = array_values($query->wheres);
            }
        }

    }
}