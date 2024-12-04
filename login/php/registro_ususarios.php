<?php
include ('../../servidor.php');
//recibe los datos de la vista
$data = json_decode(file_get_contents("php://input"));
$nombre = $data->nombre;
$correo = $data->correo;
$carrera = $data->carrera;
$contrasena = $data->password;

$sql = "INSERT INTO Usuario (nombre, Correo, carrera, contraseña) VALUES ('$nombre', '$correo', '$carrera', '$contrasena')";
$result = $db->query($sql);

if ($result) {
    echo json_encode('exito');
} else {
    echo json_encode('error');
}
//cierra la conexión a la base de datos
$db->close();
?>
