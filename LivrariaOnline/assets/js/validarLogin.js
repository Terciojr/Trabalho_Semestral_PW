document.getElementById('formLogin').addEventListener('submit', function (e) {
    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value;

    if (email === '' || !email.includes('@') || senha === '') {
        e.preventDefault();
        alert('Preencha corretamente o e-mail e a senha.');
    }
});
