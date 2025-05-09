class Carrinho {
    constructor() {
        this.itens = JSON.parse(localStorage.getItem('carrinho')) || [];
        this.atualizarUI();
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
        
        // Atualizar também na sessão do PHP
        fetch('../controller/CarrinhoController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=atualizar&carrinho=${JSON.stringify(this.itens)}`
        });
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
                    <p>${item.preco.toFixed(2)} MZN x ${item.quantidade}</p>
                    <p>Subtotal: ${(item.preco * item.quantidade).toFixed(2)} MZN</p>
                </div>
                <button class="remove-item" data-id="${item.livroId}">Remover</button>
            </div>
        `).join('');
        
        cartTotal.textContent = `${this.calcularTotal().toFixed(2)} MZN`;
    }
}

// Inicializar carrinho quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    window.carrinho = new Carrinho();
    
    // Evento para adicionar ao carrinho
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
    });
});