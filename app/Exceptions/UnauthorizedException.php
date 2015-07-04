<?php


namespace App\Exceptions;


class UnauthorizedException extends AbstractAppException {

    protected $message = 'Please log in and try again.';
    protected $code = 401;
}