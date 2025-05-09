<?php
session_start();
require_once '../dao/LivroDAO.php';
require_once '../dao/PedidoDAO.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Processar checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido = new Pedido();
    $pedido->setUsuarioId($_SESSION['usuario_id']);
    
    $total = 0;
    $itens = [];
    
    foreach ($_POST['itens'] as $item) {
        $livroDAO = new LivroDAO();
        $livro = $livroDAO->buscarPorId($item['livro_id']);
        
        $itemPedido = new ItemPedido();
        $itemPedido->setLivroId($item['livro_id']);
        $itemPedido->setQuantidade($item['quantidade']);
        $itemPedido->setPrecoUnitario($livro['preco']);
        
        $itens[] = $itemPedido;
        $total += $item['quantidade'] * $livro['preco'];
    }
    
    $pedido->setTotal($total);
    foreach ($itens as $item) {
        $pedido->addItem($item);
    }
    
    $pedidoDAO = new PedidoDAO();
    $pedidoId = $pedidoDAO->criarPedido($pedido);
    
    // Limpar carrinho
    unset($_SESSION['carrinho']);
    
    header('Location: confirmacao.php?pedido_id=' . $pedidoId);
    exit();
}

// Obter itens do carrinho
$carrinho = $_SESSION['carrinho'] ?? [];
$livros = [];
$total = 0;

$livroDAO = new LivroDAO();
foreach ($carrinho as $item) {
    $livro = $livroDAO->buscarPorId($item['livro_id']);
    if ($livro) {
        $livro['quantidade'] = $item['quantidade'];
        $livros[] = $livro;
        $total += $livro['preco'] * $item['quantidade'];
    }
}
?>

<!-- Formulário de checkout -->
<form method="POST">
    <h2>Resumo do Pedido</h2>
    <div class="itens-pedido">
        <?php foreach ($livros as $livro): ?>
            <div class="item">
                <input type="hidden" name="itens[][livro_id]" value="<?= $livro['id'] ?>">
                <input type="hidden" name="itens[][quantidade]" value="<?= $livro['quantidade'] ?>">
                
                <h3><?= $livro['titulo'] ?></h3>
                <p>Quantidade: <?= $livro['quantidade'] ?></p>
                <p>Preço: <?= $livro['preco'] * $livro['quantidade'] ?> MZN</p>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="total">
        <h3>Total: <?= $total ?> MZN</h3>
    </div>
    
    <h2>Informações de Pagamento</h2>
    <div class="metodo-pagamento">
        <label>
            <input type="radio" name="metodo_pagamento" value="mpesa" checked> M-Pesa
        </label>
        <label>
            <input type="radio" name="metodo_pagamento" value="visa"> Visa
        </label>
        <label>
            <input type="radio" name="metodo_pagamento" value="mastercard"> Mastercard
        </label>
    </div>
    
    <button type="submit">Finalizar Compra</button>
</form>