document.addEventListener('DOMContentLoaded', function() {
    // Carrinho de compras
    let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    
    // Atualizar contador do carrinho
    function atualizarContadorCarrinho() {
        document.getElementById('cart-count').textContent = carrinho.length;
    }
    
    // Adicionar ao carrinho
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const livroId = this.getAttribute('data-id');
            // Adicionar lógica para buscar livro e adicionar ao carrinho
            carrinho.push({id: livroId, quantidade: 1});
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            atualizarContadorCarrinho();
            alert('Livro adicionado ao carrinho!');
        });
    });
    
    // Modal do carrinho
    const cartModal = document.getElementById('cart-modal');
    const cartLink = document.getElementById('cart-link');
    const closeModal = document.querySelector('.close-modal');
    
    cartLink.addEventListener('click', function(e) {
        e.preventDefault();
        cartModal.style.display = 'block';
        // Atualizar itens do carrinho no modal
    });
    
    closeModal.addEventListener('click', function() {
        cartModal.style.display = 'none';
    });
    
    // Inicializar contador
    atualizarContadorCarrinho();
});