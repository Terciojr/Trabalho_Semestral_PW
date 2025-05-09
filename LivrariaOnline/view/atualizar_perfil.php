<?php
session_start();
require_once '../dao/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_id'])) {
    $id = $_SESSION['usuario_id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    $dao = new UsuarioDAO();
    $dao->atualizarPerfil($id, $nome, $email, $telefone, $endereco);

    header('Location: perfil.php');
    exit();
}
