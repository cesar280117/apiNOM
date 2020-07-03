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
    $router->post('/empresas/login', 'EmpresaController@login');
    $router->post('/empresas', 'EmpresaController@store');
    $router->group(['middleware' => 'auth'], function () use ($router) {
        //controlador para api de empresas
        $router->get('/empresas', 'EmpresaController@index');
        $router->put('/empresas/{rfc}', 'EmpresaController@update');
        $router->get('/empresas/{rfc}', 'EmpresaController@show');
        $router->post('/empresas/logout', 'EmpresaController@logout');
        $router->get('/auth', 'EmpresaController@auth');
        //controlador para api de empleados
        $router->get('/empleados', 'EmpleadoController@index');
        $router->post('/empleados', 'EmpleadoController@store');
        $router->get('/empleados/{id}', 'EmpleadoController@show');
        $router->put('/empleados/{id}', 'EmpleadoController@update');
        $router->delete('/empleados/{id}', 'EmpleadoController@destroy');
        //controllador para api de jornadas laborales
        $router->get('/jornadas', 'JornadaController@index');
        $router->post('/jornadas', 'JornadaController@store');
        $router->get('/jornadas/{id}', 'JornadaController@show');
        $router->put('/jornadas/{id}', 'JornadaController@update');
        $router->delete('/jornadas/{id}', 'JornadaController@destroy');

    });
});
