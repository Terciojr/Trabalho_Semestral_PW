<?php
session_start();
require_once '../dao/PedidoDAO.php';

if (!isset($_GET['pedido_id']) || !isset($_SESSION['usuario_id'])) {
    header('Location: catalogo.php');
    exit();
}

$pedidoDAO = new PedidoDAO();
$pedido = $pedidoDAO->buscarPorId($_GET['pedido_id']);

if ($pedido['usuario_id'] != $_SESSION['usuario_id']) {
    header('Location: catalogo.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Compra Confirmada</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Obrigado por sua compra!</h1>
        <p>Seu pedido #<?= $pedido['id'] ?> foi recebido com sucesso.</p>
        <p>Total pago: <?= $pedido['total'] ?> MZN</p>
        <p>Status: <?= $pedido['status'] ?></p>
        <a href="catalogo.php">Voltar ao catálogo</a>
    </div>
</body>
</html>