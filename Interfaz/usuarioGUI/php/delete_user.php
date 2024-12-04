<?php
include ('../../../servidor.php');

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];

if (isset($id)) {
    $stmt = $db->prepare("DELETE FROM Usuario WHERE id_usuario = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
}
?>
