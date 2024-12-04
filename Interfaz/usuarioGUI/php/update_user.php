<?php
include('../../../servidor.php');

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

if (isset($data['id']) && isset($data['nombre']) && isset($data['Correo']) && isset($data['num_telefono'])) {
    $id = $data['id'];
    $nombre = $data['nombre'];
    $correo = $data['Correo'];
    $telefono = $data['num_telefono'] ?? 0;

    $query = "UPDATE Usuario SET nombre = ?, Correo = ?, num_telefono = ? WHERE id_usuario = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssii', $nombre, $correo, $telefono, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
?>
