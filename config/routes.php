<?php
/** @var \App\Infrastructure\SimpleRouter $router */

$router->add('GET',  '/',               'CourseController@index');
$router->add('GET',  '/cursos',         'CourseController@index');
$router->add('GET',  '/cursos/create',  'CourseController@create');
$router->add('POST', '/cursos',         'CourseController@store');
// adicione outras rotas conforme sua necessidade
