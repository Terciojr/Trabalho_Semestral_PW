document.getElementById('formCadastro').addEventListener('submit', function (e) {
    const nome = document.getElementById('nome').value.trim();
    const email = document.getElementById('email').value.trim();
    const telefone = document.getElementById('telefone').value.trim();
    const endereco = document.getElementById('endereco').value.trim();
    const senha = document.getElementById('senha').value;
    const confirmarSenha = document.getElementById('confirmar').value;

    let erros = [];

    if (nome === '') erros.push('Nome é obrigatório.');
    if (email === '' || !email.includes('@')) erros.push('E-mail inválido.');
    if (telefone === '') erros.push('Telefone é obrigatório.');
    if (endereco === '') erros.push('Endereço é obrigatório.');
    
    if (senha !== confirmar) {
        e.preventDefault();
        alert('As senhas não coincidem!');
    }

    if (erros.length > 0) {
        e.preventDefault();
        alert(erros.join('\n'));
    }


});
