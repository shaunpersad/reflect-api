<?php


namespace App\Exceptions;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubdomainNotFoundException extends NotFoundHttpException {

    protected $message = 'The requested sub-domain was not found.';
} 