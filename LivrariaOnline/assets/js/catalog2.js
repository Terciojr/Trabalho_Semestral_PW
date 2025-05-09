// Variáveis globais
let currentPage = 1;
const itemsPerPage = 12;
let currentCategory = 'todos';
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// DOM Elements
const booksContainer = document.getElementById('books-container');
const categoryTitle = document.getElementById('category-title');
const cartCount = document.getElementById('cart-count');
const cartModal = document.getElementById('cart-modal');
const cartItemsContainer = document.getElementById('cart-items');
const cartTotalAmount = document.getElementById('cart-total-amount');
const bookDetailsModal = document.getElementById('book-details-modal');

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    loadBooks();
    updateCartCount();
    
    // Filtros de categoria
    document.querySelectorAll('[data-category]').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            currentCategory = item.getAttribute('data-category');
            currentPage = 1;
            updateCategoryTitle();
            loadBooks();
        });
    });
    
    // Paginação
    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            loadBooks();
        }
    });
    
    document.getElementById('next-page').addEventListener('click', () => {
        currentPage++;
        loadBooks();
    });
    
    document.querySelectorAll('.page-number').forEach(btn => {
        btn.addEventListener('click', () => {
            currentPage = parseInt(btn.textContent);
            loadBooks();
        });
    });
    
    // Carrinho
    document.getElementById('cart-link').addEventListener('click', (e) => {
        e.preventDefault();
        openCartModal();
    });
    
    document.querySelector('.close-modal').addEventListener('click', () => {
        cartModal.style.display = 'none';
    });
    
    document.querySelector('.continue-shopping').addEventListener('click', () => {
        cartModal.style.display = 'none';
    });
    
    // Fechar modal ao clicar fora
    window.addEventListener('click', (e) => {
        if (e.target === cartModal) {
            cartModal.style.display = 'none';
        }
        if (e.target === bookDetailsModal) {
            bookDetailsModal.style.display = 'none';
        }
    });
});

// Funções
function loadBooks() {
    // Simulação de busca no servidor
    // Na implementação real, faria uma requisição AJAX
    console.log(`Carregando livros - Página ${currentPage}, Categoria: ${currentCategory}`);
    
    // Limpar container
    booksContainer.innerHTML = '';
    
    // Simular dados (na prática viria do servidor)
    const simulatedBooks = generateSimulatedBooks();
    
    // Filtrar por categoria
    const filteredBooks = currentCategory === 'todos' 
        ? simulatedBooks 
        : simulatedBooks.filter(book => book.category === currentCategory);
    
    // Paginação
    const startIndex = (currentPage - 1) * itemsPerPage;
    const paginatedBooks = filteredBooks.slice(startIndex, startIndex + itemsPerPage);
    
    // Renderizar livros
    paginatedBooks.forEach(book => {
        const bookElement = createBookElement(book);
        booksContainer.appendChild(bookElement);
    });
    
    // Atualizar paginação
    updatePagination(filteredBooks.length);
}

function generateSimulatedBooks() {
    const categories = ['ficcao', 'romance', 'aventura', 'terror', 'biografia', 'fantasia', 'suspense'];
    const books = [];
    
    for (let i = 1; i <= 50; i++) {
        const randomCategory = categories[Math.floor(Math.random() * categories.length)];
        books.push({
            id: i,
            title: `Livro Exemplo ${i}`,
            author: `Autor ${i}`,
            price: Math.floor(Math.random() * 900) + 100, // Preços entre 100 e 1000 MZN
            category: randomCategory,
            cover: 'placeholder.jpg',
            description: `Esta é uma descrição de exemplo para o Livro ${i}. Lorem ipsum dolor sit amet, consectetur adipiscing elit.`
        });
    }
    
    // Adicionar alguns livros específicos para demonstração
    books[0] = {
        id: 1,
        title: "A Revolução dos Bichos",
        author: "George Orwell",
        price: 450,
        category: "ficcao",
        cover: "placeholder.jpg",
        description: "Uma sátira sobre os mecanismos do poder e como os ideais revolucionários podem ser corrompidos."
    };
    
    books[1] = {
        id: 2,
        title: "It - A Coisa",
        author: "Stephen King",
        price: 650,
        category: "terror",
        cover: "placeholder.jpg",
        description: "Um grupo de crianças enfrenta uma entidade maligna que se alimenta de seus medos."
    };
    
    return books;
}

function createBookElement(book) {
    const bookElement = document.createElement('div');
    bookElement.className = 'book-card';
    bookElement.setAttribute('data-category', book.category);
    bookElement.innerHTML = `
        <div class="book-cover">
            <img src="${book.cover}" alt="Capa do ${book.title}">
            ${book.price < 500 ? '<div class="book-badge">Promoção</div>' : ''}
        </div>
        <div class="book-info">
            <h3 class="book-title">${book.title}</h3>
            <p class="book-author">${book.author}</p>
            <div class="book-price">${book.price} MZN</div>
            <div class="book-actions">
                <button class="add-to-cart" data-id="${book.id}">Adicionar ao Carrinho</button>
                <button class="view-details" data-id="${book.id}">Ver Detalhes</button>
            </div>
        </div>
    `;
    
    // Adicionar eventos
    bookElement.querySelector('.add-to-cart').addEventListener('click', () => addToCart(book));
    bookElement.querySelector('.view-details').addEventListener('click', () => showBookDetails(book));
    
    return bookElement;
}

function updatePagination(totalItems) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const pageNumbers = document.querySelector('.page-numbers');
    pageNumbers.innerHTML = '';
    
    // Limitar a exibição para não ficar muito longo
    if (totalPages <= 5) {
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = `page-number ${i === currentPage ? 'active' : ''}`;
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', () => {
                currentPage = i;
                loadBooks();
            });
            pageNumbers.appendChild(pageBtn);
        }
    } else {
        // Lógica para exibir páginas com "..." quando há muitas
        // Implementação simplificada
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);
        
        if (startPage > 1) {
            const firstPage = document.createElement('button');
            firstPage.className = 'page-number';
            firstPage.textContent = '1';
            firstPage.addEventListener('click', () => {
                currentPage = 1;
                loadBooks();
            });
            pageNumbers.appendChild(firstPage);
            
            if (startPage > 2) {
                const dots = document.createElement('span');
                dots.textContent = '...';
                pageNumbers.appendChild(dots);
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = `page-number ${i === currentPage ? 'active' : ''}`;
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', () => {
                currentPage = i;
                loadBooks();
            });
            pageNumbers.appendChild(pageBtn);
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const dots = document.createElement('span');
                dots.textContent = '...';
                pageNumbers.appendChild(dots);
            }
            
            const lastPage = document.createElement('button');
            lastPage.className = 'page-number';
            lastPage.textContent = totalPages;
            lastPage.addEventListener('click', () => {
                currentPage = totalPages;
                loadBooks();
            });
            pageNumbers.appendChild(lastPage);
        }
    }
    
    // Atualizar botões de navegação
    document.getElementById('prev-page').disabled = currentPage === 1;
    document.getElementById('next-page').disabled = currentPage === totalPages;
}

function updateCategoryTitle() {
    const categoryNames = {
        'todos': 'Todos os Livros',
        'ficcao': 'Ficção',
        'romance': 'Romance',
        'aventura': 'Aventura',
        'terror': 'Terror',
        'biografia': 'Biografia',
        'fantasia': 'Fantasia',
        'suspense': 'Suspense'
    };
    
    categoryTitle.textContent = categoryNames[currentCategory] || 'Livros';
}

function addToCart(book, quantity = 1) {
    const existingItem = cart.find(item => item.id === book.id);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            id: book.id,
            title: book.title,
            price: book.price,
            quantity: quantity,
            cover: book.cover
        });
    }
    
    updateCart();
    showCartNotification(book.title);
}

function updateCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    
    if (cartModal.style.display === 'block') {
        renderCartItems();
    }
}

function updateCartCount() {
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    cartCount.textContent = totalItems;
}

function openCartModal() {
    renderCartItems();
    cartModal.style.display = 'block';
}

function renderCartItems() {
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<div class="empty-cart">Seu carrinho está vazio</div>';
        cartTotalAmount.textContent = '0 MZN';
        return;
    }
    
    cartItemsContainer.innerHTML = '';
    
    cart.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.innerHTML = `
            <div class="cart-item-cover">
                <img src="${item.cover}" alt="${item.title}">
            </div>
            <div class="cart-item-info">
                <h4>${item.title}</h4>
                <p>${item.price} MZN</p>
                <div class="cart-item-quantity">
                    <button class="quantity-minus" data-id="${item.id}">-</button>
                    <span>${item.quantity}</span>
                    <button class="quantity-plus" data-id="${item.id}">+</button>
                    <button class="remove-item" data-id="${item.id}">&times;</button>
                </div>
                <p class="cart-item-subtotal">Subtotal: ${item.price * item.quantity} MZN</p>
            </div>
        `;
        
        cartItemsContainer.appendChild(cartItem);
    });
    
    // Calcular total
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    cartTotalAmount.textContent = `${total} MZN`;
    
    // Adicionar eventos
    document.querySelectorAll('.quantity-minus').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = parseInt(btn.getAttribute('data-id'));
            updateCartItemQuantity(id, -1);
        });
    });
    
    document.querySelectorAll('.quantity-plus').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = parseInt(btn.getAttribute('data-id'));
            updateCartItemQuantity(id, 1);
        });
    });
    
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = parseInt(btn.getAttribute('data-id'));
            removeCartItem(id);
        });
    });
}

function updateCartItemQuantity(id, change) {
    const item = cart.find(item => item.id === id);
    if (!item) return;
    
    item.quantity += change;
    
    if (item.quantity <= 0) {
        cart = cart.filter(item => item.id !== id);
    }
    
    updateCart();
}

function removeCartItem(id) {
    cart = cart.filter(item => item.id !== id);
    updateCart();
}

function showCartNotification(bookTitle) {
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.innerHTML = `
        <p>${bookTitle} foi adicionado ao carrinho!</p>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 500);
    }, 2000);
}

function showBookDetails(book) {
    document.getElementById('detail-title').textContent = book.title;
    document.getElementById('detail-author').textContent = book.author;
    document.getElementById('detail-category').textContent = book.category;
    document.getElementById('detail-price').textContent = `${book.price} MZN`;
    document.getElementById('detail-description').textContent = book.description;
    document.getElementById('detail-quantity').value = 1;
    
    // Adicionar evento ao botão de adicionar ao carrinho no modal
    const addToCartBtn = document.querySelector('.book-details-modal .add-to-cart');
    addToCartBtn.onclick = () => {
        const quantity = parseInt(document.getElementById('detail-quantity').value);
        addToCart(book, quantity);
        bookDetailsModal.style.display = 'none';
    };
    
    // Eventos do seletor de quantidade
    document.querySelector('.quantity-minus').addEventListener('click', () => {
        const quantityInput = document.getElementById('detail-quantity');
        if (parseInt(quantityInput.value) > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    });
    
    document.querySelector('.quantity-plus').addEventListener('click', () => {
        const quantityInput = document.getElementById('detail-quantity');
        quantityInput.value = parseInt(quantityInput.value) + 1;
    });
    
    bookDetailsModal.style.display = 'block';
}