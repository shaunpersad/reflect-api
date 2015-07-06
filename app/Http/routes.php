<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function() use($app) {

    return $app->welcome();
});

$app->group(['prefix' => 'v1', 'middleware' => 'token', 'namespace' => 'App\Http\Controllers\Api'], function() use($app) {

    $app->get('/', 'SwaggerController@getIndex');
    $app->get('/docs', 'SwaggerController@getDocs');

    $app->post('/auth/logout', 'Auth\AuthController@postLogout');
    $app->post('/auth/register', 'Auth\AuthController@postRegister');
    $app->post('/auth/request-password-reset', 'Auth\AuthController@postRequestPasswordReset');
    $app->post('/auth/reset-password', 'Auth\AuthController@postResetPassword');
    $app->post('/auth/', 'Auth\AuthController@postLogin');

    $app->get('/organizations/begin-registration', 'Organizations\OrganizationsController@postBeginRegistration');
    $app->post('/organizations/complete-registration', 'Organizations\OrganizationsController@postCompleteRegistration');
    $app->get('/organizations/{organization_id}', 'Organizations\OrganizationsController@getOne');
    $app->get('/organizations', 'Organizations\OrganizationsController@getAll');
});