<?php
require_once '../dao/PedidoDAO.php';

$pedidoId = $_GET['id'] ?? 0;
$pedidoDAO = new PedidoDAO();
$pedido = $pedidoDAO->buscarPorId($pedidoId);
$itens = $pedidoDAO->buscarItensPedido($pedidoId);

if (!$pedido) {
    header('Location: tabelaDePedidos.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido #<?= $pedido['id'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-box {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info-box h3 {
            margin-top: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .total {
            text-align: right;
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 15px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .book-cover {
            width: 50px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detalhes do Pedido #<?= $pedido['id'] ?></h1>
            <div>
                <strong>Status:</strong> 
                <span style="color: 
                    <?= $pedido['status'] == 'Pendente' ? '#ff9800' : 
                       ($pedido['status'] == 'Processado' ? '#4CAF50' : '#f44336') ?>">
                    <?= $pedido['status'] ?>
                </span>
            </div>
        </div>

        <div class="info-box">
            <h3>Informações do Cliente</h3>
            <p><strong>Nome:</strong> <?= htmlspecialchars($pedido['nome_cliente']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($pedido['email']) ?></p>
            <p><strong>Telefone:</strong> <?= htmlspecialchars($pedido['telefone']) ?></p>
            <p><strong>Endereço:</strong> <?= htmlspecialchars($pedido['endereco']) ?></p>
        </div>

        <div class="info-box">
            <h3>Informações do Pedido</h3>
            <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
        </div>

        <h3>Itens do Pedido</h3>
        <table>
            <thead>
                <tr>
                    <th>Livro</th>
                    <th>Autor</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <td>
                            <img src="../upload/capas/<?= $item['capa'] ?>" class="book-cover">
                            <?= htmlspecialchars($item['titulo']) ?>
                        </td>
                        <td><?= htmlspecialchars($item['autor']) ?></td>
                        <td><?= number_format($item['preco_unitario'], 2, ',', '.') ?> MZN</td>
                        <td><?= $item['quantidade'] ?></td>
                        <td><?= number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.') ?> MZN</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total">
            <strong>Total do Pedido:</strong> <?= number_format($pedido['total'], 2, ',', '.') ?> MZN
        </div>

        <a href="tabelaDePedidos.php" class="back-btn">Voltar para lista de pedidos</a>
    </div>
</body>
</html>