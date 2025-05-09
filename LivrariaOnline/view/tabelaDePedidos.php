<?php
require_once '../dao/PedidoDAO.php';
require_once '../dao/UsuarioDAO.php';

// Filtros
$filtroData = $_GET['data'] ?? null;
$filtroCliente = $_GET['cliente'] ?? null;

$pedidoDAO = new PedidoDAO();
$pedidos = $pedidoDAO->listarPedidos($filtroData, $filtroCliente);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pedidos</title>
    <link rel="stylesheet" href="../../assets/css/styleTabelaPedidos.css">
    <style>
        .conteiner {
            width: 90%;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        .tabela {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .funcionalidades {
            margin: 20px 0;
            display: flex;
            gap: 15px;
        }
        .funcionalidades input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .botoes {
            display: flex;
            gap: 10px;
        }
        .botoes button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .botoes button:hover {
            background-color: #45a049;
        }
        .status-pendente {
            color: #ff9800;
            font-weight: bold;
        }
        .status-processado {
            color: #4CAF50;
            font-weight: bold;
        }
        .status-cancelado {
            color: #f44336;
            font-weight: bold;
        }
        .acao-btn {
            padding: 5px 10px;
            margin: 0 3px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .processar-btn {
            background-color: #2196F3;
            color: white;
        }
        .detalhes-btn {
            background-color: #ff9800;
            color: white;
        }
    </style>
</head>
<body>
    <div class="conteiner">
        <div class="tabela">
            <h1>Pedidos</h1>
            <form method="GET" action="">
                <div class="funcionalidades">
                    <input type="date" name="data" placeholder="Filtrar por data" value="<?= htmlspecialchars($filtroData) ?>">
                    <input type="text" name="cliente" placeholder="Filtrar por cliente" value="<?= htmlspecialchars($filtroCliente) ?>">
                    <button type="submit">Filtrar</button>
                    <button type="button" onclick="window.location.href='tabelaDePedidos.php'">Limpar</button>
                </div>
            </form>
            
            <table id="tabela">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pedidos)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">Nenhum pedido encontrado</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?= $pedido['id'] ?></td>
                                <td><?= htmlspecialchars($pedido['nome_cliente']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                                <td><?= number_format($pedido['total'], 2, ',', '.') ?> MZN</td>
                                <td class="status-<?= strtolower($pedido['status']) ?>"><?= $pedido['status'] ?></td>
                                <td>
                                    <button class="acao-btn detalhes-btn" 
                                            onclick="window.location.href='detalhesPedido.php?id=<?= $pedido['id'] ?>'">
                                        Detalhes
                                    </button>
                                    <?php if ($pedido['status'] == 'Pendente'): ?>
                                        <button class="acao-btn processar-btn" 
                                                onclick="processarPedido(<?= $pedido['id'] ?>)">
                                            Processar
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function processarPedido(pedidoId) {
            if (confirm('Deseja realmente processar este pedido?')) {
                fetch('../controller/PedidoController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=processar&id=${pedidoId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Pedido processado com sucesso!');
                        window.location.reload();
                    } else {
                        alert('Erro ao processar pedido: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erro ao processar pedido');
                });
            }
        }
    </script>
</body>
</html>