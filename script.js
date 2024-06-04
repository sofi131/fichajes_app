document.getElementById('logout').addEventListener('click', function () {
    // Destroy the session and redirect the user to the login page
    session_destroy();
    window.location.href = 'index.php';
});

// Función para mostrar los fichajes del día
function showFichajes() {
    // Consultar la base de datos para obtener los fichajes del día
    //...
    // Mostrar los fichajes en la lista
    var fichajesList = document.getElementById('fichajes');
    fichajesList.innerHTML = '';
    //...
}
// Evento para mostrar los fichajes al hacer clic en el botón "Entrar"
document.getElementById('entrar').addEventListener('click', showFichajes);
