<?php
session_start();
require_once '../dao/UsuarioDAO.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    if (empty($email)) {
        $erro = "Por favor, insira seu email";
    } else {
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscarPorEmail($email);
        
        if ($usuario) {
            // Gerar token de recuperação (exemplo simples)
            $token = bin2hex(random_bytes(32));
            $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Salvar token no banco de dados
            if ($usuarioDAO->salvarTokenRecuperacao($usuario['id'], $token, $expiracao)) {
                // Enviar email (simulado)
                $mensagem = "Um link de recuperação foi enviado para seu email";
                
                // Em produção, você enviaria um email real:
                /*
                $assunto = "Recuperação de Senha - Livraria Online";
                $link = "https://seusite.com/nova_senha.php?token=$token";
                $corpo = "Clique no link para redefinir sua senha: $link";
                mail($email, $assunto, $corpo);
                */
            } else {
                $erro = "Erro ao processar solicitação";
            }
        } else {
            $erro = "Email não encontrado em nosso sistema";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha - Livraria Online</title>
    <link rel="stylesheet" href="../../assets/css/styleLogin.css">
    <style>
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
    </style>
</head>
<body>
    <div class="conteiner">
        <div class="recuperacao">
            <h2>Recuperação de Senha</h2>
            
            <?php if (!empty($mensagem)): ?>
                <div class="mensagem sucesso"><?= $mensagem ?></div>
            <?php endif; ?>
            
            <?php if (!empty($erro)): ?>
                <div class="mensagem erro"><?= $erro ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <label for="email">Email cadastrado:</label>
                <input type="email" name="email" id="email" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="Digite seu email">
                
                <button type="submit">Enviar Link de Recuperação</button>
                <a href="login.php">Voltar ao Login</a>
            </form>
        </div>
    </div>
</body>
</html>