<?php
$servername = "localhost";
$username = "root";
$password = "admin";
$database = "tecmu";

// Establece la conexión a la base de datos
$db = new mysqli($servername, $username, $password, $database);

if ($db->connect_error) {
    die("La conexión a la base de datos falló: " . $conn->connect_error);
}
?> 
