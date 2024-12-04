<?php
header('Content-Type: application/json');
include('../../../servidor.php');
//comprobar que tiene sesion iniciada
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../../login/index.html');
    exit();
}
//obtener el id de la sesion
$id = $_SESSION['id'];


$consulta = "SELECT * FROM producto WHERE id_user = '$id'";
$stmt = $db->prepare($consulta);

if ($stmt) {
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $resultado = $stmt->get_result();

    $productos = [];

    while ($fila = $resultado->fetch_assoc()) {
        //verifica si el producto esta disponible
        $fila['imagen'] = base64_encode($fila['imagen']); // Encode image to base64

        // Agregar información de si el producto está comprado o no
        $comprado = $fila['visible'] == 1 ? true : false;
        $fila['comprado'] = $comprado;
        
        $productos[] = $fila;
    }

    echo json_encode($productos);

    $stmt->close();
} else {
    echo json_encode(['error' => $db->error]);
}

$db->close();
