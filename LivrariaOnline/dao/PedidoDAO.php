<?php
require_once '../model/Pedido.php';
require_once '../model/ItemPedido.php';
require_once '../config/Conexao.php';

class PedidoDAO {
    public function criarPedido(Pedido $pedido) {
        $pdo = Conexao::getConexao();
        $pdo->beginTransaction();
        
        try {
            // Inserir o pedido
            $sql = "INSERT INTO pedidos (usuario_id, total, status) 
                    VALUES (:usuario_id, :total, 'pendente')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':usuario_id', $pedido->getUsuarioId());
            $stmt->bindValue(':total', $pedido->getTotal());
            $stmt->execute();
            
            $pedidoId = $pdo->lastInsertId();
            
            // Inserir itens do pedido
            foreach ($pedido->getItens() as $item) {
                $sql = "INSERT INTO itens_pedido (pedido_id, livro_id, quantidade, preco_unitario)
                        VALUES (:pedido_id, :livro_id, :quantidade, :preco_unitario)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':pedido_id', $pedidoId);
                $stmt->bindValue(':livro_id', $item->getLivroId());
                $stmt->bindValue(':quantidade', $item->getQuantidade());
                $stmt->bindValue(':preco_unitario', $item->getPrecoUnitario());
                $stmt->execute();
                
                // Atualizar estoque
                $sql = "UPDATE livros SET estoque = estoque - :quantidade WHERE id = :livro_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':quantidade', $item->getQuantidade());
                $stmt->bindValue(':livro_id', $item->getLivroId());
                $stmt->execute();
            }
            
            $pdo->commit();
            return $pedidoId;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function criarPedido($usuarioId, $total, $itens) {
        $pdo = Conexao::getConexao();
        $pdo->beginTransaction();
        
        try {
            // Inserir o pedido principal
            $sql = "INSERT INTO pedidos (usuario_id, total) VALUES (:usuario_id, :total)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':usuario_id', $usuarioId);
            $stmt->bindValue(':total', $total);
            $stmt->execute();
            
            $pedidoId = $pdo->lastInsertId();
            
            // Inserir itens do pedido
            foreach ($itens as $item) {
                $sql = "INSERT INTO itens_pedido (pedido_id, livro_id, quantidade, preco_unitario) 
                        VALUES (:pedido_id, :livro_id, :quantidade, :preco_unitario)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':pedido_id', $pedidoId);
                $stmt->bindValue(':livro_id', $item['livro_id']);
                $stmt->bindValue(':quantidade', $item['quantidade']);
                $stmt->bindValue(':preco_unitario', $item['preco']);
                $stmt->execute();
                
                // Atualizar estoque
                $sql = "UPDATE livros SET estoque = estoque - :quantidade WHERE id = :livro_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':quantidade', $item['quantidade']);
                $stmt->bindValue(':livro_id', $item['livro_id']);
                $stmt->execute();
            }
            
            $pdo->commit();
            return $pedidoId;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Erro ao criar pedido: " . $e->getMessage());
            return false;
        }
    }

    public function listarPedidosPorUsuario($usuarioId) {
        $sql = "SELECT p.id, p.data_pedido, p.total, p.status 
                FROM pedidos p
                WHERE p.usuario_id = :usuario_id
                ORDER BY p.data_pedido DESC";
        
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId);
        $stmt->execute();
        
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Buscar itens para cada pedido
        foreach ($pedidos as &$pedido) {
            $pedido['itens'] = $this->buscarItensPedido($pedido['id']);
        }
        
        return $pedidos;
    }
}