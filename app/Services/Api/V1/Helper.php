<?php


namespace App\Services\Api\V1;


class Helper {

    /**
     * @var Api
     */
    protected $api;

    public function __construct(Api $api) {
        $this->api = $api;
    }

    /**
     * @param $value
     * @return array
     */
    public function toArray($value) {

        if (is_array($value)) {
            return $value;
        }
        if (is_string($value)) {

            if (str_contains($value,',')) {

                return array_map('trim', explode(',', $value));
            } else {
                return [$value];
            }
        }
        return [];
    }
} 