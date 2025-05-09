document.getElementById('formCliente').addEventListener('submit', function(e) {
    const senha = document.getElementById('senha').value;
    const confirmar = document.getElementById('confirmar').value;
    
    if (senha !== confirmar) {
        e.preventDefault();
        alert('As senhas não coincidem!');
    }
});