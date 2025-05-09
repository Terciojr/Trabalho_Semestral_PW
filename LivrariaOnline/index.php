<?php
session_start();
require_once 'config/Conexao.php';

// Roteamento básico
$pagina = isset($_GET['p']) ? $_GET['p'] : 'home';

switch ($pagina) {
    case 'login':
        require 'view/login.php';
        break;
    case 'catalogo':
        require 'view/catalogo.php';
        break;
    default:
        require 'view/catalogo.php';
}