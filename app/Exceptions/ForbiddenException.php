<?php


namespace App\Exceptions;


class ForbiddenException extends AbstractAppException {

    protected $message = 'You do not have permission to do this.';
    protected $code = 403;

} 