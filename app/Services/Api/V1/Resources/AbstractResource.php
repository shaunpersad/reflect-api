<?php


namespace App\Services\Api\V1\Resources;


use App\Exceptions\BadRequestException;
use App\Services\Api\V1\Api;
use App;

abstract class AbstractResource {

    /**
     * Parent Api object.
     *
     * @var Api
     */
    protected $api;

    /**
     * The parent resource.
     *
     * e.g. for /users/{user_id}/recipients,
     * the current resource would be the UsersRecipientsResource,
     * while the parent resource would be the UsersResource
     *
     * @var AbstractResource|null
     */
    protected $parent_resource;

    public function __construct(Api $api, AbstractResource $parent_resource = null) {

        $this->api = $api;
        $this->parent_resource = $parent_resource;
    }

    /**
     * Given the input params, their defaults, and the rules for validation,
     * this method will merge the params and the defaults, then validate them.
     *
     * Optionally, you may use the defaultValidationRules method to define some default rules
     * that may be used whenever this function is called. These default rules are overridden if
     * a corresponding rule is found in the $rules array.
     *
     * @param array $defaults
     * @param array $params
     * @param array $rules
     * @param array $messages
     * @throws \App\Exceptions\BadRequestException
     * @return array
     */
    public function validateParams($defaults = array(), $params = array(), $rules = array(), $messages = array()) {

        $params = array_merge($defaults, $params);

        $default_rules = $this->defaultValidationRules();

        foreach ($params as $key => $value) {

            if (array_key_exists($key, $default_rules) && !array_key_exists($key, $rules)) {
                $rules[$key] = $default_rules[$key];
            }
        }

        $validator = $this->api->validation_factory->make($params, $rules, $messages);

        if ($validator->fails()) {

            $validator->failed();
            $message = $validator->messages()->first();
            throw with(new BadRequestException($message))->meta($validator->errors()->toArray());
        }
        return $params;
    }

    public function defaultValidationRules() {

        return array();
    }

    public function createCacheKey($params = array()) {

        $obj = array(
            'class' => get_called_class(),
            'params' => $params
        );
        return json_encode($obj);
    }
} 