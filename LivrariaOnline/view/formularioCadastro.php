<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Livro</title>
    <link rel="stylesheet" href="../assets/css/styleFormularioCadastro.css">
</head>
<body>
    <div class="conteiner">
        <div class="formulario">
            <form id="formCadastro" action="/LivrariaOnline/controller/LivroController.php" method="POST" enctype="multipart/form-data">
                <label for="titulo">Título:</label>
                <input type="text" name="titulo" id="titulo" required><br>

                <label for="autor">Autor:</label>
                <input type="text" name="autor" id="autor" required><br>

                <label for="genero">Gênero:</label>
                <select name="genero" id="genero" required>
                    <option value="">Selecione um Gênero:</option>
                    <option value="ficcao">Ficção</option>
                    <option value="romance">Romance</option>
                    <option value="aventura">Aventura</option>
                    <option value="terror">Terror</option>
                    <option value="biografia">Biografia</option>
                    <option value="fantasia">Fantasia</option>
                    <option value="suspense">Suspense</option>
                </select><br>

                <label for="quantidade">Quantidade em Estoque:</label>
                <input type="number" name="quantidade" id="quantidade" min="1" required><br>

                 <label for="preco">Preço (MZN):</label>
                <input type="number" name="preco" id="preco" min="0" step="0.01" required><br>

                <label for="descricao">Descrição:</label>
                <textarea name="descricao" id="descricao" required placeholder="Insira uma descrição detalhada do livro..."></textarea><br>

                <label for="capa">Capa:</label>
                <input type="file" name="capa" id="capa" accept="image/*" required>

                <button type="submit">Gravar Livro</button>
                <button type="reset">Cancelar</button>
            </form>
        </div>
    </div>
    <script src="../../assets/js/validarCadastro.js"></script>
</body>
</html>
