<?php
include('../../../servidor.php');
session_start();

if(!isset($_SESSION['usuario'])){
    header('Location: ../../../login/index.html');
    exit();
}

$id = $_SESSION['id'];
$usuario = $_SESSION['usuario'];

$consulta = "SELECT * FROM Usuario WHERE id_usuario = '$id'";
$resultado = mysqli_query($db, $consulta);
header('Content-Type: application/json');

if($resultado->num_rows > 0){
    $row = $resultado->fetch_assoc();
    echo json_encode([
        'sesion' => 'exito',
        'id' => $id,   
        'nom' => $_SESSION['usuario'],
        'correo' => $row['Correo'],      
    ]);
} else {
    echo json_encode(['sesion' => 'error']);
}

$db->close();
?>
