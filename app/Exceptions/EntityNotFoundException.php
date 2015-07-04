<?php


namespace App\Exceptions;


class EntityNotFoundException extends AbstractAppException {

    protected $message = 'The requested entity was not found.';
    protected $code = 404;

} 