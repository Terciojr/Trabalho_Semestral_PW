document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            
            if (!email) {
                e.preventDefault();
                alert('Por favor, insira seu email');
                return false;
            }
            
            // Validação simples de email
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                e.preventDefault();
                alert('Por favor, insira um email válido');
                return false;
            }
            
            return true;
        });
    }
    
    // Para a página de nova senha
    const senhaInput = document.getElementById('senha');
    const confirmarInput = document.getElementById('confirmar');
    
    if (senhaInput && confirmarInput) {
        [senhaInput, confirmarInput].forEach(input => {
            input.addEventListener('input', function() {
                if (senhaInput.value !== confirmarInput.value) {
                    confirmarInput.setCustomValidity('As senhas não coincidem');
                } else {
                    confirmarInput.setCustomValidity('');
                }
            });
        });
    }
});