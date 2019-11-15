<?php

$router->get('', 'PageController@index');


$router->post('register',   'AuthController@createUser');
$router->post('login',      'AuthController@loginUser');

$router->post('validateToken', 'AuthController@validateToken');
$router->post('updateUser',    'AuthController@updateUser');

