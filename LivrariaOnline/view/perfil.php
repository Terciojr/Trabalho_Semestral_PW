<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Inclui arquivos necessários
require_once '../dao/UsuarioDAO.php';
require_once '../dao/PedidoDAO.php';

// Instancia os DAOs
$usuarioDAO = new UsuarioDAO();
$pedidoDAO = new PedidoDAO();

// Busca os dados do usuário
$usuario = $usuarioDAO->buscarPorId($_SESSION['usuario_id']);
$pedidos = $pedidoDAO->buscarPorUsuario($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="../assets/css/stylePerfil.css">
</head>
<body>
    
<div class="container">
    <div class="perfil-conteriner">
        <h2>Meu Perfil</h2>

        <form id="formPerfil" method="POST" action="atualizar_perfil.php">
            <div class="campo">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" disabled>
            </div>

            <div class="campo">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" disabled>
            </div>

            <div class="campo">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>" disabled>
            </div>

            <div class="campo">
                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($usuario['endereco']) ?>" disabled>
            </div>

            <div class="botoes">
                <button type="button" id="editarBtn">Editar</button>
                <button type="submit" id="salvarBtn" style="display:none;">Salvar</button>
                <button type="button" id="cancelarBtn" style="display:none;">Cancelar</button>
            </div>
        </form>
    </div>

    <div class="hisrico">
        <h1>Histórico de Compras</h1>
        <table class="listadepedidos" border="1">
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Total</th>
                <th>Status</th>
                <th>Ver Detalhes</th>
            </tr>
            <?php foreach ($pedidos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= $p['data'] ?></td>
                    <td><?= $p['total'] ?> MZN</td>
                    <td><?= $p['status'] ?></td>
                    <td><a href="detalhes_pedido.php?id=<?= $p['id'] ?>">Ver</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script src="../assets/js/perfil.js"></script>
</body>
</html>
