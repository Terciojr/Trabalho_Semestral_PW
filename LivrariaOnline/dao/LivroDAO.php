<?php
require_once '../model/Livro.php';
require_once '../config/Conexao.php';

class LivroDAO {
    public function cadastrar(Livro $livro) {
        $sql = "INSERT INTO livros (titulo, autor, genero, preco, estoque, capa, descricao) 
                VALUES (:titulo, :autor, :genero, :preco, :estoque, :capa, :descricao)";
        
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->bindValue(':titulo', $livro->getTitulo());
        $stmt->bindValue(':autor', $livro->getAutor());
        $stmt->bindValue(':genero', $livro->getGenero());
        $stmt->bindValue(':preco', $livro->getPreco());
        $stmt->bindValue(':estoque', $livro->getEstoque());
        $stmt->bindValue(':capa', $livro->getCapa());
        $stmt->bindValue(':descricao', $livro->getDescricao());
        
        return $stmt->execute();
    }

    public function listarTodos() {
        $sql = "SELECT * FROM livros";
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorCategoria($categoria) {
        $sql = "SELECT * FROM livros WHERE genero = :genero";
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->bindValue(':genero', $categoria);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarComPaginacao($pagina = 1, $itensPorPagina = 10, $filtroGenero = null, $ordenacao = 'relevance') {
        $offset = ($pagina - 1) * $itensPorPagina;
        
        // Construir a query base
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM livros";
        
        // Adicionar filtro por categoria se existir
        $params = [];
        if ($filtroGenero && $filtroGenero != 'todos') {
            $sql .= " WHERE genero = :genero";
            $params[':genero'] = $filtroGenero;
        }
        
        // Adicionar ordenação
        switch ($ordenacao) {
            case 'price-asc':
                $sql .= " ORDER BY preco ASC";
                break;
            case 'price-desc':
                $sql .= " ORDER BY preco DESC";
                break;
            case 'title-asc':
                $sql .= " ORDER BY titulo ASC";
                break;
            case 'title-desc':
                $sql .= " ORDER BY titulo DESC";
                break;
            default:
                $sql .= " ORDER BY id DESC"; // Ordem padrão (relevância)
        }
        
        $sql .= " LIMIT :offset, :itensPorPagina";
        
        $stmt = Conexao::getConexao()->prepare($sql);
        
        // Bind dos parâmetros
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
        }
        
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':itensPorPagina', $itensPorPagina, PDO::PARAM_INT);
        $stmt->execute();
        
        $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obter total de itens (considerando filtros)
        $stmt = Conexao::getConexao()->query("SELECT FOUND_ROWS() AS total");
        $totalItens = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return [
            'livros' => $livros,
            'totalItens' => $totalItens,
            'totalPaginas' => ceil($totalItens / $itensPorPagina),
            'paginaAtual' => $pagina
        ];
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM livros WHERE id = :id";
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorTermo($termo) {
        $sql = "SELECT * FROM livros 
                WHERE titulo LIKE :termo 
                OR autor LIKE :termo 
                OR descricao LIKE :termo";
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->bindValue(':termo', "%$termo%");
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}