<?php


namespace App\Exceptions;


class BadRequestException extends AbstractAppException {

    protected $message = 'Please check your submitted values.';
    protected $code = 400;
}