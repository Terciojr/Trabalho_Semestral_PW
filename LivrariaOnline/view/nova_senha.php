<?php
session_start();
require_once '../dao/UsuarioDAO.php';

$token = $_GET['token'] ?? '';
$usuarioDAO = new UsuarioDAO();
$usuario = $usuarioDAO->buscarPorToken($token);

if (!$usuario) {
    header('Location: recuperacaoSenha.php?erro=Token inválido ou expirado');
    exit();
}

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';
    
    if (empty($senha) || empty($confirmar)) {
        $erro = "Por favor, preencha ambos os campos";
    } elseif ($senha !== $confirmar) {
        $erro = "As senhas não coincidem";
    } elseif (strlen($senha) < 8) {
        $erro = "A senha deve ter pelo menos 8 caracteres";
    } else {
        if ($usuarioDAO->atualizarSenha($usuario['id'], $senha)) {
            $mensagem = "Senha alterada com sucesso!";
            // Redirecionar após 3 segundos
            header("Refresh: 3; url=login.php");
        } else {
            $erro = "Erro ao atualizar senha";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha - Livraria Online</title>
    <link rel="stylesheet" href="../../assets/css/styleLogin.css">
    <style>
        /* Mesmos estilos da recuperacaoSenha.php */
        .conteiner {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .recuperacao {
            text-align: center;
        }
        .recuperacao h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .recuperacao form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .recuperacao label {
            text-align: left;
            font-weight: bold;
            color: #555;
        }
        .recuperacao input {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .recuperacao button {
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .recuperacao button:hover {
            background-color: #45a049;
        }
        .recuperacao a {
            color: #4CAF50;
            text-decoration: none;
            margin-top: 10px;
            display: inline-block;
        }
        .recuperacao a:hover {
            text-decoration: underline;
        }
        .mensagem {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            text-align: center;
        }
        .mensagem.sucesso {
            background-color: #d4edda;
            color: #155724;
        }
        .mensagem.erro {
            background-color: #f8d7da;
            color: #721c24;
        }
        .requisitos-senha {
            text-align: left;
            font-size: 0.9em;
            color: #666;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="conteiner">
        <div class="recuperacao">
            <h2>Crie uma Nova Senha</h2>
            
            <?php if (!empty($mensagem)): ?>
                <div class="mensagem sucesso"><?= $mensagem ?></div>
            <?php endif; ?>
            
            <?php if (!empty($erro)): ?>
                <div class="mensagem erro"><?= $erro ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <label for="senha">Nova Senha:</label>
                <input type="password" name="senha" id="senha" required
                       placeholder="Mínimo 8 caracteres">
                <div class="requisitos-senha">A senha deve ter pelo menos 8 caracteres</div>
                
                <label for="confirmar">Confirmar Nova Senha:</label>
                <input type="password" name="confirmar" id="confirmar" required
                       placeholder="Digite a senha novamente">
                
                <button type="submit">Redefinir Senha</button>
                <a href="login.php">Voltar ao Login</a>
            </form>
        </div>
    </div>
</body>
</html>