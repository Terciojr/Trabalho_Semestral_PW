<?php
session_start();
require_once '../dao/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $dao = new UsuarioDAO();
    $usuario = $dao->buscarPorEmail($email);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];
        header('Location: perfil.php');
        exit();
    } else {
        $erro = "Email ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/styleLogin.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h2>Login</h2>
            <?php if (isset($erro)): ?>
                <div class="erro"><?= $erro ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
                
                <label for="senha">Senha:</label>
                <input type="password" name="senha" required>
                
                <button type="submit">Entrar</button>
            </form>
            
            <div class="links">
                <a href="cadastroCliente.php">Criar conta</a>
                <a href="recuperacaoSenha.php">Esqueci a senha</a>
            </div>
        </div>
    </div>
</body>
</html>