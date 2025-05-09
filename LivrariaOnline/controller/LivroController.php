<?php
require_once '../model/Livro.php';
require_once '../dao/LivroDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validações básicas dos campos
    $camposObrigatorios = ['titulo', 'autor', 'genero', 'preco', 'quantidade', 'descricao'];
    foreach ($camposObrigatorios as $campo) {
        if (empty($_POST[$campo])) {
            header('Location: ../view/formularioCadastro.php?error=' . urlencode("O campo $campo é obrigatório"));
            exit();
        }
    }

    // Sanitização dos inputs
    $titulo = htmlspecialchars($_POST['titulo']);
    $autor = htmlspecialchars($_POST['autor']);
    $genero = htmlspecialchars($_POST['genero']);
    $preco = (float)$_POST['preco'];
    $quantidade = (int)$_POST['quantidade'];
    $descricao = htmlspecialchars($_POST['descricao']);

    // Validações específicas
    if ($preco <= 0) {
        header('Location: ../view/formularioCadastro.php?error=' . urlencode("O preço deve ser maior que zero"));
        exit();
    }

    if ($quantidade <= 0) {
        header('Location: ../view/formularioCadastro.php?error=' . urlencode("A quantidade deve ser maior que zero"));
        exit();
    }

    // Criar objeto Livro
    $livro = new Livro();
    $livro->setTitulo($titulo);
    $livro->setAutor($autor);
    $livro->setGenero($genero);
    $livro->setPreco($preco);
    $livro->setEstoque($quantidade);
    $livro->setDescricao($descricao);

    // Processamento do upload da imagem
    if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../upload/capas/';
        
        // Verificar se o diretório existe, se não, criar
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Obter extensão do arquivo
        $extensao = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
        
        // Extensões permitidas
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($extensao, $extensoesPermitidas)) {
            header('Location: ../view/formularioCadastro.php?error=' . urlencode("Tipo de arquivo não permitido. Use JPG, JPEG, PNG ou GIF"));
            exit();
        }

        // Gerar nome único para o arquivo
        $nomeArquivo = uniqid() . '.' . $extensao;
        $caminhoCompleto = $uploadDir . $nomeArquivo;
        
        // Mover o arquivo para o diretório de uploads
        if (move_uploaded_file($_FILES['capa']['tmp_name'], $caminhoCompleto)) {
            $livro->setCapa($nomeArquivo);
        } else {
            header('Location: ../view/formularioCadastro.php?error=' . urlencode("Erro ao fazer upload da imagem"));
            exit();
        }
    } else {
        header('Location: ../view/formularioCadastro.php?error=' . urlencode("A capa do livro é obrigatória"));
        exit();
    }

    // Cadastrar no banco de dados
    $dao = new LivroDAO();
    if ($dao->cadastrar($livro)) {
        header('Location: ../view/listar_livros.php?success=1');
    } else {
        header('Location: ../view/formularioCadastro.php?error=' . urlencode("Erro ao cadastrar livro no banco de dados"));
    }
} else {
    // Se não for POST, redireciona
    header('Location: ../view/formularioCadastro.php');
}