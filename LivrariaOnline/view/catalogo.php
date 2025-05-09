<?php
require_once '../dao/LivroDAO.php';
require_once '../config/Conexao.php';

// Configurações de paginação
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$itensPorPagina = 12;
$genero = isset($_GET['genero']) ? $_GET['genero'] : null;

// Obter livros do banco de dados
$livroDAO = new LivroDAO();
$resultado = $livroDAO->listarComPaginacao($paginaAtual, $itensPorPagina, $genero);

$livros = $resultado['livros'];
$totalPaginas = $resultado['totalPaginas'];

// Definir título da categoria
$tituloCategoria = $genero ? ucfirst($genero) : 'Todos os Livros';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livraria Online - Catálogo</title>
    <link rel="stylesheet" href="../assets/css/styleCatalogo1.css">
    <style>
        .book-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff5722;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
        }
        .book-card {
            position: relative;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Livraria Online</div>
        <ul class="nav-links">
            <li><a href="catalogo.php" class="active">Início</a></li>
            <li><a href="catalogo.php">Todos os Livros</a></li>
            <li class="dropdown">
                <a href="#">Categorias</a>
                <ul class="dropdown-menu">
                    <li><a href="catalogo.php?genero=todos">Todos</a></li>
                    <li><a href="catalogo.php?genero=ficcao">Ficção</a></li>
                    <li><a href="catalogo.php?genero=romance">Romance</a></li>
                    <li><a href="catalogo.php?genero=aventura">Aventura</a></li>
                    <li><a href="catalogo.php?genero=terror">Terror</a></li>
                    <li><a href="catalogo.php?genero=biografia">Biografia</a></li>
                    <li><a href="catalogo.php?genero=fantasia">Fantasia</a></li>
                    <li><a href="catalogo.php?genero=suspense">Suspense</a></li>
                </ul>
            </li>
            <li><a href="#">Sobre Nós</a></li>
            <li><a href="#">Contactos</a></li>
        </ul>
        <div class="cart-icon">
            <a href="#" id="cart-link">Carrinho (<span id="cart-count">0</span>)</a>
        </div>
    </nav>

    <!-- Banner de Destaque -->
    <div class="banner">
        <h1>Descubra Seu Próximo Livro Favorito</h1>
        <p>Promoções especiais esta semana!</p>
    </div>

    <!-- Filtros e Busca -->
    <div class="filters">
        <div class="search-box">
            <input type="text" id="search-input" placeholder="Pesquisar livros...">
            <button id="search-btn">Buscar</button>
        </div>
        <div class="sorting">
            <label for="sort-by">Ordenar por:</label>
            <select id="sort-by">
                <option value="relevance">Relevância</option>
                <option value="price-asc">Preço: Menor para Maior</option>
                <option value="price-desc">Preço: Maior para Menor</option>
                <option value="title-asc">Título (A-Z)</option>
                <option value="title-desc">Título (Z-A)</option>
            </select>
        </div>
    </div>

    <!-- Catálogo de Livros -->
    <div class="catalog-container">
        <h2 id="category-title"><?= $tituloCategoria ?></h2>
        
        <div class="books-grid" id="books-container">
            <?php if (empty($livros)): ?>
                <p>Nenhum livro encontrado nesta categoria.</p>
            <?php else: ?>
                <?php foreach ($livros as $livro): ?>
                    <div class="book-card" data-category="<?= $livro['genero'] ?>">
                        <div class="book-cover">
                            <img src="../upload/capas/<?= $livro['capa'] ?>" alt="Capa do Livro <?= $livro['titulo'] ?>">
                            <?php if ($livro['preco'] < 500): ?>
                                <div class="book-badge">Promoção</div>
                            <?php endif; ?>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title"><?= $livro['titulo'] ?></h3>
                            <p class="book-author"><?= $livro['autor'] ?></p>
                            <div class="book-price"><?= number_format($livro['preco'], 2, ',', '.') ?> MZN</div>
                            <div class="book-actions">
                                <button class="add-to-cart" data-id="<?= $livro['id'] ?>">Adicionar ao Carrinho</button>
                                <button class="view-details" 
                                    data-id="<?= $livro['id'] ?>"
                                    data-title="<?= htmlspecialchars($livro['titulo']) ?>"
                                    data-author="<?= htmlspecialchars($livro['autor']) ?>"
                                    data-category="<?= htmlspecialchars($livro['genero']) ?>"
                                    data-price="<?= $livro['preco'] ?>"
                                    data-stock="<?= $livro['estoque'] ?>"
                                    data-description="<?= htmlspecialchars($livro['descricao'] ?? 'Descrição não disponível') ?>"
                                    data-image="../upload/capas/<?= $livro['capa'] ?>">
                                    Ver Detalhes
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Paginação -->
        <div class="pagination">
            <?php if ($paginaAtual > 1): ?>
                <a href="?pagina=<?= $paginaAtual - 1 ?><?= $genero ? '&genero='.$genero : '' ?>" class="page-nav">&laquo; Anterior</a>
            <?php endif; ?>
            
            <div class="page-numbers">
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <a href="?pagina=<?= $i ?><?= $genero ? '&genero='.$genero : '' ?>" class="page-number <?= $i == $paginaAtual ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            
            <?php if ($paginaAtual < $totalPaginas): ?>
                <a href="?pagina=<?= $paginaAtual + 1 ?><?= $genero ? '&genero='.$genero : '' ?>" class="page-nav">Próxima &raquo;</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal do Carrinho -->
    <div class="cart-modal" id="cart-modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Seu Carrinho</h2>
            <div class="cart-items" id="cart-items">
                <div class="empty-cart">Seu carrinho está vazio</div>
            </div>
            <div class="cart-summary">
                <div class="cart-total">
                    <span>Total:</span>
                    <span id="cart-total-amount">0 MZN</span>
                </div>
                <button class="checkout-btn">Finalizar Compra</button>
                <button class="continue-shopping">Continuar Comprando</button>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes do Livro -->
    <div class="book-details-modal" id="book-details-modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="book-details-container">
                <div class="book-details-cover">
                    <img src="" alt="Capa do Livro" id="detail-image">
                </div>
                <div class="book-details-info">
                    <h2 id="detail-title"></h2>
                    <p class="detail-author">Por <span id="detail-author"></span></p>
                    <p class="detail-category">Categoria: <span id="detail-category"></span></p>
                    <p class="detail-price">Preço: <span id="detail-price"></span> MZN</p>
                    <p class="detail-stock">Disponibilidade: <span id="detail-stock"></span></p>
                    <div class="detail-description">
                        <h3>Sinopse</h3>
                        <p id="detail-description"></p>
                    </div>
                    <div class="detail-actions">
                        <div class="quantity-selector">
                            <button class="quantity-minus">-</button>
                            <input type="number" value="1" min="1" id="detail-quantity">
                            <button class="quantity-plus">+</button>
                        </div>
                        <button class="add-to-cart" id="detail-add-to-cart">Adicionar ao Carrinho</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <footer class="footer">
        <div class="footer-section">
            <h3>Sobre Nós</h3>
            <p>A Livraria Online é a maior plataforma de venda de livros em Moçambique.</p>
        </div>
        <div class="footer-section">
            <h3>Contactos</h3>
            <p>Email: trcmanjate@gmail.com</p>
            <p>Email: eniaboavidasitoesitoe@gmail.com</p>
            <p>Telefone: +258 84 239 1359</p>
        </div>
        <div class="footer-section">
            <h3>Formas de Pagamento</h3>
            <div class="payment-methods">
                <span>MPesa</span>
                <span>Visa</span>
                <span>Mastercard</span>
                <span>Transferência</span>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="../assets/js/catalog.js"></script>
    <script>
        // Gerenciamento do carrinho
        class Carrinho {
            constructor() {
                this.itens = JSON.parse(localStorage.getItem('carrinho')) || [];
            }
            
            adicionarItem(livroId, titulo, preco, quantidade = 1, imagem) {
                const itemExistente = this.itens.find(item => item.livroId == livroId);
                
                if (itemExistente) {
                    itemExistente.quantidade += quantidade;
                } else {
                    this.itens.push({ 
                        livroId, 
                        titulo, 
                        preco, 
                        quantidade,
                        imagem
                    });
                }
                
                this.salvar();
            }
            
            removerItem(livroId) {
                this.itens = this.itens.filter(item => item.livroId != livroId);
                this.salvar();
            }
            
            atualizarQuantidade(livroId, quantidade) {
                const item = this.itens.find(item => item.livroId == livroId);
                if (item) {
                    item.quantidade = quantidade;
                    this.salvar();
                }
            }
            
            limpar() {
                this.itens = [];
                this.salvar();
            }
            
            calcularTotal() {
                return this.itens.reduce((total, item) => total + (item.preco * item.quantidade), 0);
            }
            
            salvar() {
                localStorage.setItem('carrinho', JSON.stringify(this.itens));
                this.atualizarUI();
            }
            
            atualizarUI() {
                // Atualizar contador
                const totalItens = this.itens.reduce((total, item) => total + item.quantidade, 0);
                document.getElementById('cart-count').textContent = totalItens;
                
                // Atualizar modal do carrinho
                const cartItems = document.getElementById('cart-items');
                const cartTotal = document.getElementById('cart-total-amount');
                
                if (this.itens.length === 0) {
                    cartItems.innerHTML = '<div class="empty-cart">Seu carrinho está vazio</div>';
                    cartTotal.textContent = '0 MZN';
                    return;
                }
                
                cartItems.innerHTML = this.itens.map(item => `
                    <div class="cart-item">
                        <img src="${item.imagem}" alt="${item.titulo}">
                        <div class="cart-item-info">
                            <h4>${item.titulo}</h4>
                            <p>${item.preco} MZN x ${item.quantidade}</p>
                            <p>Subtotal: ${(item.preco * item.quantidade).toFixed(2)} MZN</p>
                        </div>
                        <button class="remove-item" data-id="${item.livroId}">Remover</button>
                    </div>
                `).join('');
                
                cartTotal.textContent = `${this.calcularTotal().toFixed(2)} MZN`;
            }
        }

        // Inicializar carrinho
        const carrinho = new Carrinho();
        carrinho.atualizarUI();

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Adicionar ao carrinho
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-to-cart')) {
                    const livroId = e.target.dataset.id;
                    const livroCard = e.target.closest('.book-card');
                    const titulo = livroCard.querySelector('.book-title').textContent;
                    const preco = parseFloat(livroCard.querySelector('.book-price').textContent.replace(' MZN', '').replace(',', '.'));
                    const imagem = livroCard.querySelector('img').src;
                    
                    carrinho.adicionarItem(livroId, titulo, preco, 1, imagem);
                    alert('Livro adicionado ao carrinho!');
                }
                
                // Remover item do carrinho
                if (e.target.classList.contains('remove-item')) {
                    const livroId = e.target.dataset.id;
                    carrinho.removerItem(livroId);
                }
            });
            
            // Modal do carrinho
            const cartModal = document.getElementById('cart-modal');
            const cartLink = document.getElementById('cart-link');
            const closeModal = document.querySelectorAll('.close-modal');
            
            cartLink.addEventListener('click', function(e) {
                e.preventDefault();
                cartModal.style.display = 'block';
            });
            
            closeModal.forEach(btn => {
                btn.addEventListener('click', function() {
                    cartModal.style.display = 'none';
                    document.getElementById('book-details-modal').style.display = 'none';
                });
            });
            
            // Modal de detalhes do livro
            const bookModal = document.getElementById('book-details-modal');
            
            document.querySelectorAll('.view-details').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('detail-title').textContent = this.dataset.title;
                    document.getElementById('detail-author').textContent = this.dataset.author;
                    document.getElementById('detail-category').textContent = this.dataset.category;
                    document.getElementById('detail-price').textContent = parseFloat(this.dataset.price).toFixed(2);
                    document.getElementById('detail-stock').textContent = `Em estoque (${this.dataset.stock} unidades)`;
                    document.getElementById('detail-description').textContent = this.dataset.description;
                    document.getElementById('detail-image').src = this.dataset.image;
                    document.getElementById('detail-add-to-cart').dataset.id = this.dataset.id;
                    
                    bookModal.style.display = 'block';
                });
            });
            
            // Adicionar do modal de detalhes
            document.getElementById('detail-add-to-cart').addEventListener('click', function() {
                const livroId = this.dataset.id;
                const titulo = document.getElementById('detail-title').textContent;
                const preco = parseFloat(document.getElementById('detail-price').textContent);
                const quantidade = parseInt(document.getElementById('detail-quantity').value);
                const imagem = document.getElementById('detail-image').src;
                
                carrinho.adicionarItem(livroId, titulo, preco, quantidade, imagem);
                alert(`${quantidade} ${quantidade > 1 ? 'unidades' : 'unidade'} adicionada(s) ao carrinho!`);
            });
            
            // Controle de quantidade
            document.querySelector('.quantity-plus').addEventListener('click', function() {
                const input = document.getElementById('detail-quantity');
                input.value = parseInt(input.value) + 1;
            });
            
            document.querySelector('.quantity-minus').addEventListener('click', function() {
                const input = document.getElementById('detail-quantity');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                }
            });
            
            // Finalizar compra
            document.querySelector('.checkout-btn').addEventListener('click', function() {
                if (carrinho.itens.length > 0) {
                    window.location.href = 'checkout.php';
                } else {
                    alert('Seu carrinho está vazio!');
                }
            });
        });
    </script>
</body>
</html>