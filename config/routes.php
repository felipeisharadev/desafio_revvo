<?php
// config/routes.php
// Aqui, $router é uma instância de App\Infrastructure\SimpleRouter

/**
 * Rotas de Exemplo para o CRUD de Cursos
 * Formato: $router->add(HTTP_METHOD, URI, 'ControllerName@actionMethod');
 */

// Home Page: Lista todos os cursos
$router->add('GET', '/', 'CursoController@index');

// Formulário para criar um novo curso
$router->add('GET', '/cursos/novo', 'CursoController@create');

// Rota para processar a submissão do formulário
$router->add('POST', '/cursos', 'CursoController@store'); 

// Rota para visualizar um curso específico (usando um placeholder de ID)
// NOTA: O SimpleRouter atual não suporta placeholders como {id},
// mas mantemos a rota como lembrete de um CRUD completo.
$router->add('GET', '/cursos/view', 'CursoController@show');