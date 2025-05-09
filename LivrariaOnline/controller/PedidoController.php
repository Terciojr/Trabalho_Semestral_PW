<?php
require_once '../dao/PedidoDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $pedidoDAO = new PedidoDAO();
    
    switch ($action) {
        case 'processar':
            $pedidoId = $_POST['id'] ?? 0;
            if ($pedidoDAO->processarPedido($pedidoId)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Falha ao processar pedido']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Ação inválida']);
    }
    exit();
}