document.getElementById('editarBtn').addEventListener('click', function () {
    document.querySelectorAll('#formPerfil input').forEach(input => input.disabled = false);
    document.getElementById('salvarBtn').style.display = 'inline';
    document.getElementById('cancelarBtn').style.display = 'inline';
    this.style.display = 'none';
});

document.getElementById('cancelarBtn').addEventListener('click', function () {
    location.reload(); // Ou restaurar valores originais manualmente
});
const editarBtn = document.getElementById("editarBtn");
const salvarBtn = document.getElementById("salvarBtn");
const cancelarBtn = document.getElementById("cancelarBtn");
const inputs = document.querySelectorAll("#formPerfil input");

editarBtn.addEventListener("click", () => {
    inputs.forEach(input => input.disabled = false);
    editarBtn.style.display = "none";
    salvarBtn.style.display = "inline-block";
    cancelarBtn.style.display = "inline-block";
});

cancelarBtn.addEventListener("click", () => {
    location.reload(); // Recarrega para restaurar valores originais
});
