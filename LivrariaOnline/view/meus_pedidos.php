<?php
session_start();
require_once '../dao/PedidoDAO.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$pedidoDAO = new PedidoDAO();
$pedidos = $pedidoDAO->listarPedidosPorUsuario($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos</title>
    <link rel="stylesheet" href="../assets/css/stylePedidos.css">
    <style>
        .pedido-container {
            max-width: 1000px;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        .pedido-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .pedido-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .pedido-id {
            font-weight: bold;
            font-size: 1.2em;
        }
        .pedido-data {
            color: #666;
        }
        .pedido-status {
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }
        .status-pendente {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-processado {
            background-color: #d4edda;
            color: #155724;
        }
        .pedido-total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
        }
        .pedido-itens {
            margin-top: 10px;
        }
        .item {
            display: flex;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .item-img {
            width: 60px;
            height: 80px;
            object-fit: cover;
            margin-right: 15px;
        }
        .item-info {
            flex-grow: 1;
        }
        .item-titulo {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .item-preco {
            color: #e67e22;
        }
        .empty-pedidos {
            text-align: center;
            padding: 50px;
            font-size: 1.2em;
            color: #666;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="pedido-container">
        <h1>Meus Pedidos</h1>
        
        <?php if (empty($pedidos)): ?>
            <div class="empty-pedidos">
                Você ainda não fez nenhum pedido. <a href="catalogo.php">Voltar ao catálogo</a>
            </div>
        <?php else: ?>
            <?php foreach ($pedidos as $pedido): ?>
                <div class="pedido-card">
                    <div class="pedido-header">
                        <div>
                            <span class="pedido-id">Pedido #<?= $pedido['id'] ?></span>
                            <span class="pedido-data"> - <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></span>
                        </div>
                        <div class="pedido-status status-<?= strtolower($pedido['status']) ?>">
                            <?= $pedido['status'] ?>
                        </div>
                    </div>
                    
                    <div class="pedido-itens">
                        <?php foreach ($pedido['itens'] as $item): ?>
                            <div class="item">
                                <img src="../upload/capas/<?= $item['capa'] ?>" class="item-img">
                                <div class="item-info">
                                    <div class="item-titulo"><?= htmlspecialchars($item['titulo']) ?></div>
                                    <div>Quantidade: <?= $item['quantidade'] ?></div>
                                    <div class="item-preco"><?= number_format($item['preco_unitario'], 2, ',', '.') ?> MZN cada</div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="pedido-total">
                        Total do Pedido: <?= number_format($pedido['total'], 2, ',', '.') ?> MZN
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>