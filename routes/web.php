<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});



$router->group(['prefix' => 'api'], function () use ($router) {

$router->post('login', 'authController@login');
$router->post('/register', 'authController@register');

$router->post('sendPasswordResetLink', 'PasswordResetRequestController@sendEmail');
$router->get('/blockUser/{id}', 'authController@BlockUser');
$router->get('/unlockUser/{id}', 'authController@unlockUser');
$router->post('resetPassword', 'ChangePasswordController@passwordResetProcess');

$router->post('addRole', 'RolePerController@addRole');
$router->post('addPermission', 'RolePerController@addPermission');
$router->post('role_has_per', 'RolePerController@role_add_per');
$router->post('model_has_per', 'RolePerController@model_add_per');



$router->group(['middleware' => 'auth'], function () use ($router) {


    $router->get('/me', 'authController@me');
    $router->get('/logout', 'authController@logout');
    $router->post('/refresh', 'authController@refresh');
    $router->post('/change', 'authController@changePassword');

    $router->get('/book', 'bookController@getBook');
    $router->post('/create_book', 'bookController@create');
    $router->get('/edit-book/{id}', 'bookController@edit');
    $router->post('/update-book/{id}', 'bookController@update');
    $router->post('/delete-book', 'bookController@delete');
});
});

