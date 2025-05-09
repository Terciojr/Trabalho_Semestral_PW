<?php
session_start();
require_once '../dao/UsuarioDAO.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuarioDAO = new UsuarioDAO();
$usuario = $usuarioDAO->buscarPorId($_SESSION['usuario_id']);
?>
