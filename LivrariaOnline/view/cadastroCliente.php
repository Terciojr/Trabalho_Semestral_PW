<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="/LivrariaOnline/assets/css/styleCadastroCliente.css">
</head>
<body>
    <div class="conteiner">
        <div class="formulario">
            <form id="formCliente" action="/LivrariaOnline/controller/UsuarioController.php" method="POST">
                <label for="nome">Nome Completo:</label>
                <input type="text" name="nome" id="nome" required><br>

                <label for="morada">Morada:</label>
                <input type="text" name="morada" id="morada" required><br>

                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" required><br>

                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required><br>

                <label for="confirmar">Confirmar Senha:</label>
                <input type="password" name="confirmar" id="confirmar" required><br>

                <button type="submit">Submeter</button>
            </form>
            <a href="login.php">Já tem uma conta? Entrar</a>
        </div>
    </div>
    <script src="/LivrariaOnline/assets/js/validarCadastroCliente.js"></script>
</body>
</html>
