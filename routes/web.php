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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/usuarios/login', 'UserController@login');
    $router->post('/usuarios', 'UserController@store');
    $router->group(['middleware' => 'auth'], function () use ($router) {
        //controlador para api de usuarios
        $router->get('/usuarios', 'UserController@index');
        $router->put('/usuarios/{id}', 'UserController@update');
        $router->get('/usuarios/{id}', 'UserController@show');
        $router->delete('/usuarios/{id}', 'UserController@destroy');
        $router->post('/usuarios/logout', 'UserCOntroller@logout');
        $router->get('/auth', 'UserController@auth');
        //controlador para api de empleados
        $router->get('/empleados', 'EmpleadoController@index');
        $router->post('/empleados', 'EmpleadoController@store');
        $router->get('/empleados/{id}', 'EmpleadoController@show');
        $router->put('/empleados/{id}', 'EmpleadoController@update');
        $router->delete('/empleados/{id}', 'EmpleadoController@destroy');
    });
});
