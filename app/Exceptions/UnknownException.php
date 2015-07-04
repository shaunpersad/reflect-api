<?php


namespace App\Exceptions;


class UnknownException extends AbstractAppException {

    protected $message = 'An unknown error occurred.';
    protected $code = 500;
} 