<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

abstract class AbstractAppModel extends Model {

    public function flushCache() {

        $cache = app('Illuminate\Contracts\Cache\Repository');

        $key = $this->key();

        if ($cache->has($key)) {
            //
            $cache->forget($key);
        }

        $class = get_class($this);

        $cache->tags($class)->flush();
    }

    public static function observers() {

        self::saved(function(AbstractAppModel $model) {

            $model->flushCache();
        });

        self::deleted(function(AbstractAppModel $model) {

            $model->flushCache();
        });
    }

    public static function boot() {

        parent::boot();
        self::observers();
    }

    /**
     * Find a model by its primary key or fail.
     *
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public static function findOrFailCached($id, $columns = array('*')) {

        $class = get_called_class();

        $key = $class.'_'.$id.'_'.implode('_',$columns);

        $expires_at = 60 * 1; // 1 hour

        $cache = app('Illuminate\Contracts\Cache\Repository');
        $model = $cache->remember(
            $key,
            $expires_at,
            function() use($class, $id, $columns) {

                return $class::findOrFail($id, $columns);
            });

        return $model;
    }

    public function key($columns = array('*')) {

        return get_class($this).'_'.$this->id.'_'.implode('_',$columns);
    }

} 