<?php
/** @var \App\Infrastructure\SimpleRouter $router */

$router->add('GET',  '/',               'CursoController@index');
$router->add('GET',  '/cursos',         'CursoController@index');
$router->add('GET',  '/cursos/create',  'CursoController@create');
$router->add('POST', '/cursos',         'CursoController@store');
// adicione outras rotas conforme sua necessidade
