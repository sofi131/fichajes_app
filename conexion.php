<?php
$host = 'localhost';
$username = 'root';
$password = '1234';
$dbname = 'fichajes_app';

// Crear una conexión a la base de datos
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar si la conexión tuvo éxito
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
