<?php
require_once '../model/Usuario.php';
require_once '../dao/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha'])) {
        // Cadastro de novo usuário
        $usuario = new Usuario();
        $usuario->setNome($_POST['nome']);
        $usuario->setEmail($_POST['email']);
        $usuario->setSenha($_POST['senha']);
        
        if (isset($_POST['telefone'])) $usuario->setTelefone($_POST['telefone']);
        if (isset($_POST['endereco'])) $usuario->setEndereco($_POST['endereco']);
        
        $dao = new UsuarioDAO();
        if ($dao->cadastrar($usuario)) {
            header('Location: ../view/login.php?success=1');
        } else {
            header('Location: ../view/cadastroCliente.php?error=1');
        }
    }
}