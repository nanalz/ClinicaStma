
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_clinica"; // Cambia esto por el nombre real

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
