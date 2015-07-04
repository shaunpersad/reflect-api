<?php


namespace App\Exceptions;


use Exception;

abstract class AbstractAppException extends Exception {


    /**
     * Anything else that should be included in the response,
     * like all the validation errors.
     *
     * @var mixed
     */
    protected $meta;

    /**
     * Create a response based on the exception.
     *
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function response() {

        return response()->json($this->errorData())->setStatusCode($this->code);
    }


    /**
     * Include any additional data via this method.
     * e.g. throw with(new ForbiddenException)->meta(['reason' => 'i hate you']);
     *
     * @param $meta
     * @return $this
     */
    public function meta($meta) {

        $this->meta = $meta;
        return $this;
    }

    /**
     * The data to send back, either as JSON or as session data.
     *
     * @return array
     */
    protected  function errorData() {
        $response = array(
            'code' => $this->code,
            'message' => $this->message,
            'exception' => get_class($this),
            'meta' => $this->meta
        );

        return $response;
    }

} 