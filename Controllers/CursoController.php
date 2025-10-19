<?php
// Controllers/CursoController.php
namespace App\Controllers;

use App\Contracts\DBConnectionInterface;
use App\Contracts\ViewRendererInterface;
use App\Core\Request; // Para receber dados de requisição

class CursoController
{
    private DBConnectionInterface $db;
    private ViewRendererInterface $renderer;

    // Injeção de Dependência via Construtor
// Controllers/CursoController.php
    public function __construct(DBConnectionInterface $db, ViewRendererInterface $renderer) // <--- ESPERA 2 ARGUMENTOS
    {
        $this->db = $db;
        $this->renderer = $renderer;
    }

    /**
     * GET / - Lista todos os cursos.
     */
    public function index(Request $request): string
    {
        // ANTES: $cursos = $this->db->execute("SELECT * FROM cursos ORDER BY id DESC");
        
        // AGORA: Usamos query() para SELECT
        $cursos = $this->db->query("SELECT * FROM cursos ORDER BY id DESC"); 
        
        return $this->renderer->render('cursos/index', [
            'title' => 'Lista de Cursos',
            'cursos' => $cursos
        ]);
    }

    /**
     * GET /cursos/novo - Mostra o formulário de criação.
     */
    public function create(): string
    {
        return $this->renderer->render('cursos/create', [
            'title' => 'Criar Novo Curso'
        ]);
    }

    /**
     * POST /cursos - Processa a criação de um novo curso.
     * * NOTA: Este é um exemplo. Em produção, você precisa de validação de dados
     * e um mecanismo de redirecionamento.
     */
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
        // Aqui precisaríamos de um roteador que extraia o ID da URI.
        // Por simplicidade, vamos assumir que o ID é 1 para este esqueleto.
        $id = 1; 

        $curso = $this->db->execute("SELECT * FROM cursos WHERE id = :id", ['id' => $id]);
        
        return $this->renderer->render('cursos/show', [
            'title' => 'Visualizar Curso',
            'curso' => $curso[0] ?? [] // Pega o primeiro resultado ou um array vazio
        ]);
    }

}