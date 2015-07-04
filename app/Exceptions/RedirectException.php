<?php


namespace App\Exceptions;


class RedirectException extends AbstractAppException {

    protected $message = 'This content exists elsewhere.';
    protected $code = 302;
}