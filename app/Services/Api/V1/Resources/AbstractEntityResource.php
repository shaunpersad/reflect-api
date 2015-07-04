<?php


namespace App\Services\Api\V1\Resources;


use App\Exceptions\BadRequestException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\UnknownException;
use App\Models\AbstractAppModel;
use App\Services\Api\V1\Api;
use App\Services\Api\V1\ApiCollection;

abstract class AbstractEntityResource extends AbstractResource {

    /**
     * This is the actual entity pulled from the database.
     *
     * e.g. for /users/{user_id},
     * the entity would be the User object.
     *
     * @var AbstractAppModel|null
     */
    protected $entity;

    /**
     * The public entity relations an API user can request via the "with" param.
     * You may use this or the private_entity_relations attribute, or both.
     *
     * @var array
     */
    protected $public_entity_relations = array();

    /**
     * The private entity relations an API user has no access to via the "with" param.
     * You may use this or the public_entity_relations attribute, or both.
     *
     * @var array
     */
    protected $private_entity_relations = array();

    /**
     * The relations to load with every entity returned.
     *
     * @var array
     */
    protected $with = array();

    /**
     * @param Api $api
     * @param AbstractAppModel $entity
     * @param AbstractResource $parent_resource
     */
    public function __construct(Api $api, AbstractAppModel $entity = null, AbstractResource $parent_resource = null) {

        parent::__construct($api, $parent_resource);

        $this->entity = $entity;
        $resource = $this;
        $this->api->validation_factory->extend('with', function($attribute, $value, $parameters) use($resource) {

            $resource->validateRequestedEntityRelations($value);
            return true;
        });

    }

    /**
     * @return mixed
     */
    abstract public function query();

    abstract public function all($params = array());

    public function defaultAction($params = array()) {

        if (!empty($this->entity)) {
            return $this->get($params);
        }
        return $this->all($params = array());
    }

    public function get($params = array()) {

        $defaults = array(

            'with' => $with = null
        );

        $params = $this->validateParams($defaults, $params);

        extract($params);

        return $this->entity();
    }

    public function remove($params = array()) {

        $entity = $this->entity();

        if ($entity->delete()) {

            return $entity;
        }
        throw new UnknownException();
    }

    /**
     * @throws \App\Exceptions\EntityNotFoundException
     * @return mixed
     */
    public function entity() {

        if (empty($this->entity)) {

            throw new EntityNotFoundException();
        }

        return $this->entity->load($this->with);
    }

    /**
     * @param $query
     * @param $count_column
     * @param $page
     * @param $per_page
     * @param $order_by
     * @param $order_dir
     * @return ApiCollection
     */
    public function paginate($query, $count_column, $page, $per_page, $order_by, $order_dir) {

        $start = ($page - 1) * $per_page;

        $query->orderBy($order_by, $order_dir);

        $total_num_results = $query->count($count_column);
        $collection = $query->skip($start)->take($per_page)->get();

        return new ApiCollection($collection, $page, $per_page, $total_num_results);
    }

    public function defaultValidationRules() {

        return array(
            'with' => array('sometimes', 'array', 'with'),
            'include_featured' => array('sometimes', 'boolean'),
            'order_by' => array('required', 'not_in:password,remember_token'),
            'order_dir' => array('required', 'in:'.implode(',', array('asc', 'desc', 'ASC', 'DESC'))),
            'page' => array('required', 'integer', 'min:1'),
            'per_page' => array('required', 'integer', 'max:1000', 'min:0'),
        );
    }

    public function validateRequestedEntityRelations($with) {

        if (is_array($with)) {

            if (count(array_diff($with, $this->public_entity_relations))) {

                throw new BadRequestException('Invalid relationships found in "with".');
            }

            if (count(array_intersect($with, $this->private_entity_relations))) {

                throw new BadRequestException('Invalid relationships found in "with".');
            }
            $this->with = $with;
        }

    }
} 