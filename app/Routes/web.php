<?php
/** @var \App\Infrastructure\SimpleRouter $router */

$router->add('GET',  '/',                   'CourseController@index');
$router->add('GET',  '/cursos',             'CourseController@index');
$router->add('GET',  '/cursos/create',      'CourseController@create');
$router->add('GET',  '/cursos/edit/{id}',   'CourseController@edit');
$router->add('POST', '/cursos',             'CourseController@store');
$router->add('POST', '/cursos/delete/{id}', 'CourseController@delete');
$router->add('POST', '/cursos/update/{id}', 'CourseController@update');
