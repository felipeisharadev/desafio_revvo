<?php
namespace App\Controllers;

use App\Contracts\DBConnectionInterface;
use App\Contracts\ViewRendererInterface;
use App\Core\Request; 

class CursoController
{
    private DBConnectionInterface $db;
    private ViewRendererInterface $renderer;

    public function __construct(DBConnectionInterface $db, ViewRendererInterface $renderer) // <--- ESPERA 2 ARGUMENTOS
    {
        $this->db = $db;
        $this->renderer = $renderer;
    }

    public function index(Request $request): string
    {
        // 1. CHAMA O NOVO MÉTODO DE LEITURA (query)
        $cursos = $this->db->query("SELECT * FROM cursos ORDER BY id DESC"); 
        
        // --- INÍCIO DA LINHA DE DEBUG TEMPORÁRIA ---
        // Verificamos se o array está vazio e o que a query retornou.
        if (empty($cursos)) {
            error_log("DEBUG: A consulta 'SELECT * FROM cursos' retornou 0 resultados.");
            error_log("DEBUG: Tabela de cursos pode estar vazia ou a query falhou silenciosamente.");
        } else {
            error_log("DEBUG: Consulta retornou " . count($cursos) . " cursos.");
            // Opcional: print_r($cursos); para ver o conteúdo
        }
        // --- FIM DA LINHA DE DEBUG TEMPORÁRIA ---
        
        return $this->renderer->render('cursos/index', [
            'title' => 'Lista de Cursos',
            'cursos' => $cursos
        ]);
    }

    public function create(): string
    {
        return $this->renderer->render('cursos/create', [
            'title' => 'Criar Novo Curso'
        ]);
    }

    public function store(Request $request): string
    {
        $data = $request->body; 
        
        $sql = "INSERT INTO cursos (nome, descricao, carga_horaria) VALUES (:nome, :descricao, :carga)";

        $linhasAfetadas = $this->db->exec($sql, [
            'nome' => $data['nome'] ?? '',
            'descricao' => $data['descricao'] ?? '',
            'carga' => $data['carga_horaria'] ?? 0 
        ]); 
        
        $ultimoId = $this->db->lastInsertId(); 
        
        return $this->renderer->render('debug', [
            'message' => "Curso salvo com sucesso. Linhas afetadas: {$linhasAfetadas}. ID inserido: {$ultimoId}",
            'POST_data' => $data 
        ]); 
    }
    
    public function show(Request $request): string
    {
        $id = 1; 

        $curso = $this->db->execute("SELECT * FROM cursos WHERE id = :id", ['id' => $id]);
        
        return $this->renderer->render('cursos/show', [
            'title' => 'Visualizar Curso',
            'curso' => $curso[0] ?? [] 
        ]);
    }

}