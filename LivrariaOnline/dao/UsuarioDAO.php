<?php
require_once '../model/Usuario.php';
require_once '../config/Conexao.php';

class UsuarioDAO {
    public function cadastrar(Usuario $usuario) {
        $sql = "INSERT INTO usuarios (nome, email, telefone, endereco, senha) 
                VALUES (:nome, :email, :telefone, :endereco, :senha)";
        
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->bindValue(':nome', $usuario->getNome());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':telefone', $usuario->getTelefone());
        $stmt->bindValue(':endereco', $usuario->getEndereco());
        $stmt->bindValue(':senha', $usuario->getSenha());
        
        return $stmt->execute();
    }

    public function buscarPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarPerfil($id, $nome, $email, $telefone, $endereco) {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, 
                telefone = :telefone, endereco = :endereco WHERE id = :id";
        
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':endereco', $endereco);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }

    public function salvarTokenRecuperacao($usuarioId, $token, $expiracao) {
        $sql = "UPDATE usuarios 
            SET token_recuperacao = :token, 
                token_expiracao = :expiracao 
            WHERE id = :id";
    
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':expiracao', $expiracao);
        $stmt->bindValue(':id', $usuarioId);
    
        return $stmt->execute();
    }

    public function buscarPorToken($token) {
        $sql = "SELECT * FROM usuarios 
                WHERE token_recuperacao = :token 
                AND token_expiracao > NOW()";
        
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarSenha($usuarioId, $novaSenha) {
        $sql = "UPDATE usuarios 
                SET senha = :senha,
                    token_recuperacao = NULL,
                    token_expiracao = NULL
                WHERE id = :id";
        
        $stmt = Conexao::getConexao()->prepare($sql);
        $stmt->bindValue(':senha', password_hash($novaSenha, PASSWORD_DEFAULT));
        $stmt->bindValue(':id', $usuarioId);
        
        return $stmt->execute();
    }
}