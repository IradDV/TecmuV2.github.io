<?php
$servername = "autorack.proxy.rlwy.net";
$username = "root";
$password = "FhNmukQvdkJPxBGmpITWgjzxlvzkvJoA";
$database = "railway";
$port = 42132;

// Establece la conexión a la base de datos
$db = new mysqli($servername, $username, $password, $database, $port);

if ($db->connect_error) {
    die("La conexión a la base de datos falló: " . $conn->connect_error);
}
?> 
