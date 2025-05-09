<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'atualizar':
            $carrinho = json_decode($_POST['carrinho'], true);
            $_SESSION['carrinho'] = $carrinho;
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Ação inválida']);
    }
}