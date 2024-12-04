<?php
include('../../../servidor.php');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $id_producto = $_POST['id'];
    $calificacion = $_POST['calificacion'];
    $comentario = $_POST['comentario'];

    // Preparar la consulta para insertar la calificación en la base de datos
    $consulta = $db->prepare("INSERT INTO calificacion_producto (calificacion, comentario, id_producto) VALUES (?, ?, ?)");
    $consulta->bind_param('isi', $calificacion, $comentario, $id_producto);

    // Ejecutar la consulta
    if ($consulta->execute()) {
        echo json_encode('exito');
    } else {
        echo json_encode('error');
    }

    $consulta->close();
} else {
    echo json_encode(['error' => 'No se ha enviado el formulario.']);
}

$db->close();
?>
