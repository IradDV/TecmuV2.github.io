<?php
include ('../../servidor.php');

$data = json_decode(file_get_contents("php://input"));
$correo = $data->correo;
$contrasena = $data->password;

if ($correo === 'admin' && $contrasena === 'admin') {
    echo json_encode('admin');
    return;
}
if ($correo === 'visitante' && $contrasena === 'visitante') {
    echo json_encode('visitante');
    return;
}

$sql = "SELECT * FROM usuario WHERE correo = '$correo' AND contraseña = '$contrasena'";
$result = $db->query($sql);


if ($result->num_rows > 0) {
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION['id'] = $row['id_usuario'];
    $_SESSION['usuario'] = $row['nombre'];
    
    echo json_encode('exito');
} else {
    
    echo json_encode('error');
}
//cierra la conexión a la base de datos
$db->close();
?>
