<?php
require_once '../../dao/LivroDAO.php';
$livroDAO = new LivroDAO();
$livros = $livroDAO->listarTodos();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Livros</title>
    <link rel="stylesheet" href="../../assets/css/styleTabela.css">
</head>
<body>
    <div class="conteiner">
        <div class="tabela">
            <h1>Lista de Livros</h1>
            <table id="tabela" border="1">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Gênero</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                </tr>
                <?php foreach ($livros as $livro): ?>
                <tr>
                    <td><?= $livro['id'] ?></td>
                    <td><?= $livro['titulo'] ?></td>
                    <td><?= $livro['autor'] ?></td>
                    <td><?= $livro['genero'] ?></td>
                    <td><?= $livro['preco'] ?> MZN</td>
                    <td><?= $livro['estoque'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="funcionalidades">
            <input type="text" name="ordenacao" placeholder="Insira a ordem que deseja">
            <input type="text" name="buscaLivro" placeholder="Insira o livro que deseja procurar">
            <input type="text" name="buscaAutor" placeholder="Insira o autor que deseja procurar">            
        </div>
        <div class="botoes">
            <button class="editar">Editar</button>
            <button class="excluir">Excluir</button>
        </div>
    </div>
</body>
</html>
